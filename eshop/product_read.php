<?php
include 'config/database.php';

$tableContent = '';
$category_option = '';
// delete message prompt will be here

// select all data

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
    $tableContent = $tableContent . "<tr>" .
        "<td>" . $row['product_id'] . "</td>"
        . "<td>" . $row['name'] . "</td>"
        . "<td>" . $row['description'] . "</td>"
        . "<td>" . $row['category_name'] . "</td>"
        . "<td>" . $row['price'] . "</td>"
        . "<td class='d-flex justify-content-between'>"
        . "<a href='product_read_one.php?id={$row['product_id']}' class='btn btn-info'>Read</a>"
        . "<a href='product_update.php?id={$row['product_id']}' class='btn btn-primary'>Edit</a>"
        . "<a href='#' onclick='delete_product({$row['product_id']});'  class='btn btn-danger'>Delete</a>"
        . "</td></tr>";
}

if (isset($_POST['search'])) {

    $category_option = $_POST['category'];

    $tableContent = '';

    if ($category_option != "all_category") {
        $selectstmt = $con->prepare('SELECT categorys.category_name, products.product_id, products.name, products.description, products.price
        FROM categorys
        INNER JOIN products ON products.category_id = categorys.id WHERE category_name LIKE :category_name ORDER BY product_id DESC');
        $selectstmt->execute(array(':category_name' => $category_option));
        $rowcount = $selectstmt->rowCount();
        $table = $selectstmt->fetchAll();

        foreach ($table as $row) {
            $tableContent = $tableContent . "<tr>" .
                "<td>" . $row['product_id'] . "</td>"
                . "<td>" . $row['name'] . "</td>"
                . "<td>" . $row['description'] . "</td>"
                . "<td>" . $row['category_name'] . "</td>"
                . "<td>" . $row['price'] . "</td>"
                . "<td class='d-flex justify-content-between'>"
                . "<a href='product_read_one.php?id={$row['product_id']}' class='btn btn-info'>Read</a>"
                . "<a href='product_update.php?id={$row['product_id']}' class='btn btn-primary'>Edit</a>"
                . "<a href='#' onclick='delete_product({$row['product_id']});'  class='btn btn-danger'>Delete</a>"
                . "</td></tr>";
        }
    }

    if ($category_option == "all_category") {

        $category_option = $_POST['category'];

        $tableContent = '';

        $selectstmt = $con->prepare('SELECT categorys.category_name, products.product_id, products.name, products.description, products.price
        FROM categorys
        INNER JOIN products ON products.category_id = categorys.id ORDER BY product_id DESC');
        $selectstmt->execute();
        $rowcount = $selectstmt->rowCount();
        $table = $selectstmt->fetchAll();

        foreach ($table as $row) {
            $tableContent = $tableContent . "<tr>" .
                "<td>" . $row['product_id'] . "</td>"
                . "<td>" . $row['name'] . "</td>"
                . "<td>" . $row['description'] . "</td>"
                . "<td>" . $row['category_name'] . "</td>"
                . "<td>" . $row['price'] . "</td>"
                . "<td class='d-flex justify-content-between'>"
                . "<a href='product_read_one.php?id={$row['product_id']}' class='btn btn-info'>Read</a>"
                . "<a href='product_update.php?id={$row['product_id']}' class='btn btn-primary'>Edit</a>"
                . "<a href='#' onclick='delete_product({$row['product_id']});'  class='btn btn-danger'>Delete</a>"
                . "</td></tr>";
        }
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
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
        <a href='product_create.php' class='btn btn-primary'>Create New Product</a>
        <br><br>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
        <div class="row g-0 d-flex justify-content-center">
            <select class="fs-6 rounded col-2 me-2" name="category">
                <option value="all_category">--ALL Category--</option>
                <?php
                while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<option class='bg-white' value='$category_name'>$category_name</option>";
                }
                ?>
            </select>
            <input type="submit" value="search" name="search" class="btn-sm btn btn-danger col-1"/>
        </div>

        <br><br>

        
            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>

                <?php
                //check if more than 0 record found
                echo $tableContent;
                ?>

            </table>
        </form>
    </div> <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <!-- confirm delete record will be here -->

</body>

</html>