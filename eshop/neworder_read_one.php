<?php
// get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

//include database connection
include 'config/database.php';


// read current record's data
try {

    $query = "SELECT order_details.order_details_id, order_details.order_id, order_details.product_id, order_details.quantity, products.name FROM order_details INNER JOIN products ON order_details.product_id = products.product_id WHERE order_id = :order_id ";

    $stmt = $con->prepare($query);

    // Bind the parameter
    $stmt->bindParam(":order_id", $id);

    // execute our query
    $stmt->execute();

    $num = $stmt->rowCount();
?>

    <!DOCTYPE HTML>
    <html>

    <head>
        <title>Read one order</title>
        <!-- Latest compiled and minified Bootstrap CSS -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    </head>

    <body>

        <!-- container -->
        <div class="container">
        <div class="page-header">
            <h1>Read Order Details</h1>
        </div>

        <?php

        if ($num > 0) {

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>Order Details ID</th>";
            echo "<th>Order ID</th>";
            echo "<th>Product ID</th>";
            echo "<th>Product Name</th>";
            echo "<th>Quantity</th>";
            echo "</tr>";

            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$order_details_id}</td>";
                echo "<td>{$order_id}</td>";
                echo "<td>{$product_id}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$quantity}</td>";
                echo "</tr>";
            }

            echo "<tr>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td> <a href='neworder_read.php' class='btn btn-danger'>Back to read Order</a></td>";
            echo "</tr>";
            // end table
            echo "</table>";
        }
    }

    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
        ?>







        </div> <!-- end .container -->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    </body>

    </html>