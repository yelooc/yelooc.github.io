<?php
include 'session_login.php';
include 'config/database.php';
include 'nav.php';

$tableContent = '';
$category_option = '';

$query_category = "SELECT * FROM categorys ORDER BY c_id ASC";
$stmt_category = $con->prepare($query_category);
$stmt_category->execute();


$query = "SELECT categorys.category_name, products.p_id, products.path_img, products.name, products.description, products.price
FROM categorys
INNER JOIN products ON products.category_id = categorys.c_id ORDER BY p_id DESC";
$stmt = $con->prepare($query);
$stmt->execute();
$num = $stmt->rowCount();
$table = $stmt->fetchAll();

foreach ($table as $row) {
    extract($row);
    $price = !preg_match("/[.]/", $row['price']) ? $row['price'] . ".00" : $row['price'];
    $tableContent = $tableContent . "<tr>" .
        "<td>" . $row['p_id'] . "</td>"
        . "<td class='text-center'><img src='" . $row['path_img'] . "' style='object-fit: cover;height:100px;width:100px;'></td>"
        . "<td>" . $row['name'] . "</td>"
        . "<td>" . $row['description'] . "</td>"
        . "<td>" . $row['category_name'] . "</td>"
        . "<td class='text-end'>RM" . $price . "</td>"
        . "<td class='text-center'>"
        . "<a href='product_read_one.php?id={$row['p_id']}' class='btn btn-info me-2'>Read</a>"
        . "<a href='product_update.php?id={$row['p_id']}' class='btn btn-primary'>Edit</a>"
        . "<button onclick='myFunction_product({$p_id})' class='btn btn-danger ms-2'>Delete</button>"
        . "</td></tr>";
}

if (isset($_POST['filter'])) {

    $category_option = $_POST['category'];
    $tableContent = '';

    if ($category_option != "all_category") {
        $selectstmt = $con->prepare('SELECT categorys.category_name, products.p_id, products.path_img, products.name, products.description, products.price
        FROM categorys
        INNER JOIN products ON products.category_id = categorys.c_id WHERE category_id=:category_id ORDER BY p_id DESC');
        $selectstmt->bindParam(":category_id", $category_option);
    } else {
        $selectstmt = $con->prepare('SELECT categorys.category_name, products.p_id, products.path_img, products.name, products.description, products.price
        FROM categorys
        INNER JOIN products ON products.category_id = categorys.c_id ORDER BY p_id DESC');
    }

    $selectstmt->execute();
    $rowcount = $selectstmt->rowCount();
    $table = $selectstmt->fetchAll();

    foreach ($table as $row) {
        extract($row);
        $price = !preg_match("/[.]/", $row['price']) ? $row['price'] . ".00" : $row['price'];
        $category_header = $category_option == "all_category" ? "<td>" . $row['category_name'] . "</td>" : "";
        $tableContent = $tableContent . "<tr>" .
            "<td>" . $row['p_id'] . "</td>"
            . "<td class='text-center'><img src='" . $row['path_img'] . "' style='object-fit: cover;height:100px;width:100px;'></td>"
            . "<td>" . $row['name'] . "</td>"
            . "<td>" . $row['description'] . "</td>"
            . $category_header
            . "<td class='text-end'>RM" . $price . "</td>"
            . "<td class='text-center'>"
            . "<a href='product_read_one.php?id={$row['p_id']}' class='btn btn-info me-2'>Read</a>"
            . "<a href='product_update.php?id={$row['p_id']}' class='btn btn-primary'>Edit</a>"
            . "<button onclick='myFunction_product({$p_id})' class='btn btn-danger ms-2'>Delete</button>"
            . "</td></tr>";
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Product Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Product Listing</h1>
        </div>
        <?php
        if (isset($_GET['msg']) && $_GET['msg'] == 'delete') {
                echo "<div class='alert alert-success'>Delete Product Succesfully</div>";
            }
            ?>
        <br>
        <div class="g-0 row">
            <div class="col-sm pb-3">   
                <a href='product_create.php' class='btn btn-primary'>Create New Product</a>   
            </div>

            <div class="col-sm d-flex justify-content-sm-center">
                <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" class='row g-0'>
                    <select class="form-control form-select col" name="category">
                        <option value="all_category">--ALL Category--</option>
                        <?php
                        while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            $selected = $_POST['category'] == $c_id ? "selected" : "";
                            echo "<option class='bg-white' value='$c_id'$selected>$category_name</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" value="Filter" name="filter" class="btn btn-danger col-auto ms-2" />
                </form>
            </div>

            <div class="col d-flex justify-content-sm-end">
                <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" class='row g-0'>
                    <input name="keyword" type="search" class='form-control col' placeholder="Search..." value="<?php echo isset($_POST['keyword']) ? $_POST['keyword'] : '' ?>" />
                    <button type="submit" class="btn btn-primary col-auto ms-2" name="search">Search</button>
                </form>
            </div>


        </div>

        <br><br>
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <th>ID</th>
                <th>Image</th>
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
                $query = $con->prepare("SELECT categorys.category_name, products.p_id, products.path_img, products.name, products.description, products.price
                FROM categorys
                INNER JOIN products ON products.category_id = categorys.c_id WHERE name LIKE '%$keyword%' ORDER BY p_id DESC");
                $query->execute();

                if ($query->fetch(PDO::FETCH_ASSOC)) {
                    $query->execute();
                    while ($row = $query->fetch()) {
                        extract($row);
                        $price = !preg_match("/[.]/", $row['price']) ? $row['price'] . ".00" : $row['price'];
                        echo "</tr>";
                        echo "<td>" . $row['p_id'] . "</td>";
                        echo "<td class='text-center'><img src='" . $row['path_img'] . "' style='object-fit: cover;height:100px;width:100px;'></td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['category_name'] . "</td>";
                        echo "<td class='text-end'>RM" . $price . "</td>";
                        echo "<td class='text-center'>";
                        echo "<a href='product_read_one.php?id={$row['p_id']}' class='btn btn-info me-2'>Read</a>";
                        echo "<a href='product_update.php?id={$row['p_id']}' class='btn btn-primary'>Edit</a>";
                        echo "<button onclick='myFunction_product({$p_id})' class='btn btn-danger ms-2'>Delete</button>";
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
<script>
function myFunction_product(p_id) {
    
    let text = "Do you sure want ot delete?";
    if (confirm(text) == true) {
        window.location = "product_delete.php?id=" +p_id;
    }else{

    }
}
</script>
</html>