<?php
include 'session_login.php';
include 'config/database.php';

$tableContent = '';
$category_option = '';

$query_category = "SELECT * FROM categorys ORDER BY id ASC";
$stmt_category = $con->prepare($query_category);
$stmt_category->execute();


$query = "SELECT categorys.category_name, products.product_id, products.name, products.description, products.price
FROM categorys
INNER JOIN products ON products.category_id = categorys.id ORDER BY product_id DESC";
$stmt = $con->prepare($query);
$stmt->execute();
$num = $stmt->rowCount();
$table = $stmt->fetchAll();

foreach ($table as $row) {
    $price = !preg_match("/[.]/", $row['price']) ? $row['price'].".00" : $row['price'] ;
    $tableContent = $tableContent . "<tr>" .
        "<td>" . $row['product_id'] . "</td>"
        . "<td>" . $row['name'] . "</td>"
        . "<td>" . $row['description'] . "</td>"
        . "<td>" . $row['category_name'] . "</td>"
        . "<td class='text-end'>" . $price . "</td>"
        . "<td class='d-flex justify-content-between'>"
        . "<a href='product_read_one.php?id={$row['product_id']}' class='btn btn-info'>Read</a>"
        . "<a href='product_update.php?id={$row['product_id']}' class='btn btn-primary'>Edit</a>"
        . "<a href='#' onclick='delete_product({$row['product_id']});'  class='btn btn-danger'>Delete</a>"
        . "</td></tr>";
}

if (isset($_POST['filter'])) {

    $category_option = $_POST['category'];
    $tableContent = '';

    if ($category_option != "all_category") {
        $selectstmt = $con->prepare('SELECT categorys.category_name, products.product_id, products.name, products.description, products.price
        FROM categorys
        INNER JOIN products ON products.category_id = categorys.id WHERE category_id=:category_id ORDER BY product_id DESC');
        $selectstmt->bindParam(":category_id", $category_option);
    } else {
        $selectstmt = $con->prepare('SELECT categorys.category_name, products.product_id, products.name, products.description, products.price
        FROM categorys
        INNER JOIN products ON products.category_id = categorys.id ORDER BY product_id DESC');
    }

    $selectstmt->execute();
    $rowcount = $selectstmt->rowCount();
    $table = $selectstmt->fetchAll();

    foreach ($table as $row) {
        $price = !preg_match("/[.]/", $row['price']) ? $row['price'].".00" : $row['price'] ;
        $category_header = $category_option == "all_category" ? "<td>" . $row['category_name'] . "</td>" : "";
        $tableContent = $tableContent . "<tr>" .
            "<td>" . $row['product_id'] . "</td>"
            . "<td>" . $row['name'] . "</td>"
            . "<td>" . $row['description'] . "</td>"
            . $category_header
            . "<td class='text-end'>" . $price . "</td>"
            . "<td class='d-flex justify-content-between'>"
            . "<a href='product_read_one.php?id={$row['product_id']}' class='btn btn-info'>Read</a>"
            . "<a href='product_update.php?id={$row['product_id']}' class='btn btn-primary'>Edit</a>"
            . "<a href='#' onclick='delete_product({$row['product_id']});'  class='btn btn-danger'>Delete</a>"
            . "</td></tr>";
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Product Read - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container-fuild bg-dark">
        <div class="container">

            <nav class="navbar-expand-lg py-2">

                <div class="collapse navbar-collapse d-flex justify-content-between">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="home.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active text-white" href="#" role="button" data-bs-toggle="dropdown">
                                Product
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="product_create.php">Create Product</a></li>
                                <li><a class="dropdown-item bg-secondary" href="#">Product Listing</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-secondary" href="#" role="button" data-bs-toggle="dropdown">
                                Customer
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="customer_create.php">Create Customer</a></li>
                                <li><a class="dropdown-item" href="customer_read.php">Customer Listing</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-secondary" href="#" role="button" data-bs-toggle="dropdown">
                                Order
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="neworder_create.php">Create New Order</a></li>
                                <li><a class="dropdown-item" href="neworder_read.php">Order Listing</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="contact_us.php">Contact us</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="session_logout.php">Log Out</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1>Read Products</h1>
        </div>
        <br><br>

        <div class="row g-0">

            <div class="col">
                <a href='product_create.php' class='btn-sm btn btn-primary'>Create New Product</a>
            </div>

            <div class="col d-flex justify-content-center">
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                    <select class="rounded" name="category">
                        <option value="all_category">--ALL Category--</option>
                        <?php
                        while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            $selected = $_POST['category'] == $id ? "selected" : "";
                            echo "<option class='bg-white' value='$id'$selected>$category_name</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" value="Filter" name="filter" class="btn-sm btn btn-danger" />
                </form>
            </div>

            <div class="col d-flex justify-content-end">
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                    <input name="keyword" type="search" placeholder="Search..." value="<?php echo isset($_POST['keyword']) ? $_POST['keyword'] : '' ?>" />
                    <button type="submit" class="btn-sm btn btn-primary" name="search">Search</button>
                </form>
            </div>


        </div>

        <br><br>
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <?php
                if (!isset($_POST['filter'])) {
                    echo "<th>Category</th>";
                } else {
                    echo $category_option == "all_category" ? "<th>Category</th>" : "";
                }
                ?>
                <th class='text-end'>Price</th>
                <th>Action</th>
            </tr>

            <?php
            if (!isset($_POST['search'])) {
                echo $tableContent;
            } else {
                $keyword = $_POST['keyword'];
                $query = $con->prepare("SELECT categorys.category_name, products.product_id, products.name, products.description, products.price
                FROM categorys
                INNER JOIN products ON products.category_id = categorys.id WHERE name LIKE '%$keyword%' ORDER BY product_id DESC");
                $query->execute();
                
                if ($query->fetch(PDO::FETCH_ASSOC)) {
                    $query->execute();
                    while ($row = $query->fetch()) {
                        $price = !preg_match("/[.]/", $row['price']) ? $row['price'].".00" : $row['price'] ;
                        echo "</tr>";
                        echo "<td>" . $row['product_id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['category_name'] . "</td>";
                        echo "<td class='text-end'>" . $price . "</td>";
                        echo "<td class='d-flex justify-content-between'>";
                        echo "<a href='product_read_one.php?id={$row['product_id']}' class='btn btn-info'>Read</a>";
                        echo "<a href='product_update.php?id={$row['product_id']}' class='btn btn-primary'>Edit</a>";
                        echo "<a href='#' onclick='delete_product({$row['product_id']});'  class='btn btn-danger'>Delete</a>";
                        echo "</td></tr>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>No records found.</div>";
                }
            }
            ?>

        </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>