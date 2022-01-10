<?php
include 'session_login.php';
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
include 'config/database.php';
include 'nav.php';
?>

<?php

    try {

        $query = "SELECT products.name, products.description, products.price, products.path_img, products.promotionprice, products.manufacturedate, products.expireddate, categorys.category_name FROM products INNER JOIN categorys ON products.category_id = categorys.c_id WHERE p_id = :p_id";
        $stmt = $con->prepare($query);
        $stmt->bindParam(":p_id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
    }

    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
    ?>

<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Read Product</h1>
        </div>
        <?php
        if (isset($_GET['msg']) && $_GET['msg'] == 'productCreate_success') {
            echo "<div class='alert alert-success mt-4'>Product Created Successfully.</div>";
        }
        if (isset($_GET['msg']) && $_GET['msg'] == 'productUpdate_success') {
            echo "<div class='alert alert-success mt-4'>Product Updated Successfully.</div>";
        }
        ?>
        <br>
        <img src="<?php echo htmlspecialchars($path_img, ENT_QUOTES); ?>" style='object-fit: cover;height:100px;width:100px;'>
        <br><br>
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($name, ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Category</td>
                <td><?php echo htmlspecialchars($category_name, ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <td>Price</td>
                <?php $price_validate = !preg_match("/[.]/", $row['price']) ? $row['price'] . ".00" : $row['price']; ?>
                <td><?php echo htmlspecialchars($price_validate, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Promotion Price</td>
                <?php $p_price_validation = !preg_match("/[.]/", $row['promotionprice']) ? $row['promotionprice'] . ".00" : $row['promotionprice']; ?>
                <td><?php echo htmlspecialchars($p_price_validation, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Manufacture Date</td>
                <td><?php echo htmlspecialchars($manufacturedate, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Expired Date</td>
                <td><?php echo htmlspecialchars($expireddate, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php
                    echo "<a href='product_update.php?id=$id' class='btn btn-primary me-2'>Edit</a>";
                    echo "<a href='product_read.php' class='btn btn-danger'>Back to Product Listing</a>";
                    ?>
                </td>
            </tr>
        </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>