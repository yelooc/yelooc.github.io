<?php
include 'session_login.php';
include 'config/database.php';

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

<div class="container-fuild bg-dark">
        <div class="container">

            <nav class="navbar-expand-lg py-2">

                <div class="collapse navbar-collapse d-flex justify-content-between">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="home.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-secondary" href="#" role="button" data-bs-toggle="dropdown">
                                Product
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="product_create.php">Create Product</a></li>
                                <li><a class="dropdown-item" href="product_read.php">Product Listing</a></li>
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
                            <a class="nav-link dropdown-toggle active text-white" href="#" role="button" data-bs-toggle="dropdown">
                                Order
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="neworder_create.php">Create New Order</a></li>
                                <li><a class="dropdown-item bg-secondary" href="#">Order Listing</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="contact_us.php">Contact us</a>
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
                    echo "<a href='#' onclick='delete_neworder({$order_id});'  class='btn btn-danger'>Delete</a>";
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