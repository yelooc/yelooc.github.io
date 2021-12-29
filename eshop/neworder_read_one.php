<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Order Read One</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

</head>

<body>
<?php
   include 'nav.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Read Order Details</h1>
        </div>

        <?php

        try {

            $query1 = "SELECT order_details.order_details_id, order_details.order_id, order_details.product_id, order_details.quantity, products.name, products.price FROM order_details INNER JOIN products ON order_details.product_id = products.product_id WHERE order_id = :order_id ";

            $stmt = $con->prepare($query1);
            $stmt->bindParam(":order_id", $id);
            $stmt->execute();
            $num = $stmt->rowCount();

            $query2 = "SELECT order_summary.order_id, customers.username FROM order_summary INNER JOIN customers ON order_summary.customer_username = customers.username WHERE order_id=$id";

            $stmt2 = $con->prepare($query2);
            $stmt2->execute();
            
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $cus_username = $row2['username'];
            $orderID = $row2['order_id'];

            echo "<br><h6>Order ID : $orderID</h6>";
            echo "<br><h6>Username : $cus_username</h6>";

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table
            echo "<tr>";
            echo "<th>Order Details ID</th>";
            echo "<th>Product ID</th>";
            echo "<th>Product Name</th>";
            echo "<th class='text-end'>Quantity</th>";
            echo "<th class='text-end'>Price</th>";
            echo "<th class='text-end'>Sub Total</th>";
            echo "</tr>";

            if ($num > 0){
                
                $grand_total = 0;

                echo "<tr>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $price_decimal = !preg_match("/[.]/", $row['price']) ? $row['price'] . ".00" : $row['price'];
                $subtotal = doubleval($price_decimal) * doubleval($quantity);
                $subtotal_decimal = !preg_match("/[.]/", $subtotal) ? $subtotal . ".00" : $subtotal;
                echo "<td>$order_details_id</td>";
                echo "<td>{$product_id}</td>";
                echo "<td>{$name}</td>";
                echo "<td class='text-end'>{$quantity}</td>";
                echo "<td class='text-end'>{$price_decimal}</td>";
                echo "<td class='text-end'>{$subtotal_decimal}</td>";
                echo "</tr>";
                $grand_total = $grand_total + (doubleval($price_decimal) * doubleval($quantity));
            }
    
            echo "<tr>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td class='fw-bold text-end'>Total</td>";
            echo "<td class='fw-bold text-end'>".$grand_total."</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td class='text-center'><a href='neworder_update.php?id={$id}' class='btn btn-primary me-3'>Edit</a>";
            echo "<a href='neworder_read.php?id=$username' class='btn btn-danger'>Back to read Order</a></td>";
            echo "</tr>";

            echo "</table>";
        }
    }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>