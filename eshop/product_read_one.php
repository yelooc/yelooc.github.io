<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <?php
    include 'nav.php';
    ?>

    <?php
    try {

        $query = "SELECT * FROM products WHERE product_id = :id ";
        $stmt = $con->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
    }

    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Read Product</h1>
        </div>
        <br>
        <img src="<?php echo htmlspecialchars($path_img, ENT_QUOTES); ?>" width="100px">
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
                    if (filter_var($_SESSION['correct_username'], FILTER_VALIDATE_EMAIL)) {
                        $query = 'SELECT * from customers WHERE email= ?';
                    } else {
                        $query = 'SELECT * FROM customers WHERE username=?';
                    }

                    $stmt = $con->prepare($query);
                    $stmt->bindParam(1, $_SESSION['correct_username']);
                    $stmt->execute();
                    $numCustomer = $stmt->rowCount();

                    if ($numCustomer > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        extract($row);
                        echo "<a href='product_read.php?id=$username' class='btn btn-danger'>Back to read products</a>";
                    }
                    ?>
                </td>
            </tr>
        </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>