<?php
include 'config/database.php';

// delete message prompt will be here

// select all data
$query = "SELECT * FROM order_summary ORDER BY order_id DESC";
$stmt = $con->prepare($query);
$stmt->execute();

// this is how to get number of rows returned
$num = $stmt->rowCount();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body class="m-3">
    <div class="container">
        <div class="page-header">
            <h1>Read Order Summary</h1>
        </div>
        <br><br>
        <a href='neworder_create.php' class='btn btn-primary'>Create New Order</a>
        <br><br>
        <table class='table table-hover table-responsive table-bordered'>

            <tr>
                <th>Order ID</th>
                <th>Customer's Username</th>
                <th>Purchase Date</th>
            </tr>

            <?php
            //check if more than 0 record found
            if ($num > 0) {

                // retrieve our table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // this will make $row['firstname'] to just $firstname only
                    extract($row);
                    // creating new table row per record
                    echo "<tr>";
                    echo "<td>{$order_id}</td>";
                    echo "<td>{$customer_username}</td>";
                    echo "<td class='col-6'>{$purchase_date}</td>";
                    echo "<td class='d-flex justify-content-between'>";

                    // read one record
                    echo "<a href='neworder_read_one.php?id={$order_id}' class='btn btn-info m-r-1em'>Read</a>";

                    // we will use this links on next part of this post
                    echo "<a href='neworder_update.php?id={$order_id}' class='btn btn-primary m-r-1em'>Edit</a>";

                    // we will use this links on next part of this post
                    echo "<a href='#' onclick='delete_neworder({$order_id});'  class='btn btn-danger'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }

            echo "</table>";

            ?>

    </div> <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <!-- confirm delete record will be here -->

</body>

</html>