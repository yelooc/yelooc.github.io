<!--ID : 2030346 -->
<!--Name : NG WING Chun -->
<!--Topic : Eshop Home-->
<?php
include 'session_login.php';
include 'config/database.php';

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
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container-fuild bg-dark">
        <div class="container">

            <nav class="navbar-expand-lg py-2">

                <div class="collapse navbar-collapse d-flex justify-content-between">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="#">Home</a>
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
                            <a class="nav-link dropdown-toggle text-secondary" href="#" role="button" data-bs-toggle="dropdown">
                                Order
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="neworder_create.php">Create New Order</a></li>
                                <li><a class="dropdown-item" href="neworder_read.php">Order Listing</a></li>
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


    <h1 class="fw-bold d-flex justify-content-center p-5">Welcome</h1>

    <?php 
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
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
        

        if ($row['gender'] == 'Male') {
            echo "<p class='text-center'>Mr. ".$row['firstname']." ".$row['lastname']."</p>";
        } else {
           echo "<p class='text-center'>Ms. ".$row['firstname']." ".$row['lastname']."</p>";
        }
    }
    ?>

    <div class="text-center row p-5">
        <div class="border border-primary col me-5">
            Customers<br>
            <?php echo $rowcount_customer ?>
        </div>
        <div class="border border-primary col">
            Products<br>
            <?php echo $rowcount_product ?>
        </div>
        <div class="border border-primary text-center mt-5">
            Orders<br>
            <?php echo $rowcount_order ?>
        </div>
    </div>
    



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>