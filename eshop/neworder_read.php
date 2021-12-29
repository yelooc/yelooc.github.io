<?php
include 'session_login.php';
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
include 'config/database.php';

include 'nav.php';

$query = "SELECT * FROM order_summary ORDER BY order_id DESC";
$stmt = $con->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Neworder Read - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Read Order Summary</h1>
        </div>
        <br><br>
        <?php
        echo "<a href='neworder_create.php?id={$id}' class='btn btn-primary'>Create New Order</a>";
        ?>
        <br><br>
        <table class='table table-hover table-responsive table-bordered'>

            <tr>
                <th>Order ID</th>
                <th>Customer's Username</th>
                <th>Purchase Date</th>
                <th></th>
            </tr>

            <?php
         
            if ($num > 0) {

            
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                   
                    extract($row);
                   
                    echo "<tr>";
                    echo "<td>{$order_id}</td>";
                    echo "<td>{$customer_username}</td>";
                    echo "<td class='col-6'>{$purchase_date}</td>";
                    echo "<td class='d-flex justify-content-between'>";

                    echo "<a href='neworder_read_one.php?id={$order_id}' class='btn btn-info m-r-1em'>Read</a>";
                    echo "<a href='neworder_update.php?id={$order_id}' class='btn btn-primary m-r-1em'>Edit</a>";
                    echo "<a href='neworder_delete.php?id={$order_id}' class='btn btn-danger'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }

            echo "</table>";

            ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>