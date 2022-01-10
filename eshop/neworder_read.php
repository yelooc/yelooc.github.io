<?php
include 'session_login.php';
include 'config/database.php';
include 'nav.php';

$query = "SELECT order_summary.order_id, order_summary.customer_username, order_summary.purchase_date, order_details.modified FROM order_summary INNER JOIN order_details ON order_summary.order_id = order_details.order_id ORDER BY order_id DESC";
$stmt = $con->prepare($query);
$stmt->execute();
$num = $stmt->rowCount();

?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Order Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Order Listing</h1>
        </div>
        <?php
        if (isset($_GET['msg']) && $_GET['msg'] == 'delete') {
                echo "<div class='alert alert-success'>Delete Order Succesfully</div>";
            }
            ?>
        <br>
        
        <a href='neworder_create.php' class='btn btn-primary'>Create New Order</a>
        
        <br><br>
        <table class='table table-hover table-responsive table-bordered'>

            <tr>
                <th>Order ID</th>
                <th>Customer's Username</th>
                <th>Purchase Date</th>
                <th>Last Modified</th>
                <th>Action</th>
            </tr>

            <?php
         
            if ($num > 0) {
            
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                   
                    extract($row);
                   
                    echo "<tr>";
                    echo "<td>{$order_id}</td>";
                    echo "<td>{$customer_username}</td>";
                    echo "<td class='col-2'>{$purchase_date}</td>";
                    echo "<td>$modified</td>";
                    echo "<td class='d-flex justify-content-around'>";
                    echo "<a href='neworder_read_one.php?id={$order_id}' class='btn btn-info'>Read</a>";
                    echo "<a href='neworder_update.php?id={$order_id}' class='btn btn-primary'>Edit</a>";
                    echo "<button onclick='myFunction_neworder({$order_id})' class='btn btn-danger'>Delete</button>";
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
<script>
function myFunction_neworder(order_id) {
    
    let text = "Do you sure want ot delete?";
    if (confirm(text) == true) {
        window.location = "neworder_delete.php?id=" +order_id;
    }else{

    }
}
</script>

</html>