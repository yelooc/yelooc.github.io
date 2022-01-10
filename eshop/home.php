<!--ID : 2030346 -->
<!--Name : NG WING Chun -->
<!--Topic : Eshop Home-->
<?php
include 'session_login.php';
include 'config/database.php';
include 'nav.php';

$query_customer = "SELECT * FROM customers";
$stmt_customer = $con->prepare($query_customer);
$stmt_customer->execute();
$rowcount_customer = $stmt_customer->rowCount();

$query_product = "SELECT * FROM products";
$stmt_product = $con->prepare($query_product);
$stmt_product->execute();
$rowcount_product = $stmt_product->rowCount();

$query_summary = "SELECT * FROM order_summary";
$stmt_order = $con->prepare($query_summary);
$stmt_order->execute();
$rowcount_order = $stmt_order->rowCount();

$query_lastorder = "SELECT * FROM order_summary ORDER BY order_id DESC LIMIT 1";
$stmt_lastorder = $con->prepare($query_lastorder);
$stmt_lastorder->execute();
$lastorder = $stmt_lastorder->rowCount();

?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class='container'>
        <?php

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
        ?>

            <h1 class="fw-bold d-flex justify-content-center p-5">Welcome</h1>

        <?php
            if ($row['gender'] == 'male') {
                echo "<p class='text-center'>Mr. " . $row['firstname'] . " " . $row['lastname'] . "</p>";
            } else {
                echo "<p class='text-center'>Ms. " . $row['firstname'] . " " . $row['lastname'] . "</p>";
            }
        }
        ?>

        <div class="text-center row p-5">
            <div class="border border-primary col me-5">
                Total Customers<br>
                <?php echo $rowcount_customer ?>
            </div>
            <div class="border border-primary col">
                Total Products<br>
                <?php echo $rowcount_product ?>
            </div>
        </div>
        <div class="text-center row p-5">
            <div class="border border-primary text-center col me-5">
                Total Orders<br>
                <?php echo $rowcount_order ?>
            </div>
            <?php
            if ($lastorder > 0) {
                $row = $stmt_lastorder->fetch(PDO::FETCH_ASSOC);
            ?>
                <div class="border border-primary text-center col">
                    Last Order<br>
                    <?php
                    echo $row['order_id'] . "<br>";
                    echo $row['purchase_date']
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>