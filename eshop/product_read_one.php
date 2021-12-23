<?php
include 'session_login.php';

        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
        
        include 'config/database.php';

        try {

            $query = "SELECT * FROM products WHERE product_id = :id";
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

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
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
                            <a class="nav-link dropdown-toggle active text-white" href="#" role="button" data-bs-toggle="dropdown">
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

    <div class="container">
        <div class="page-header">
            <h1>Read Product</h1>
        </div>

        <?php 
        
        // include 'record_was_saved.php'; 
        
        // if (isset($_POST['msg']) && $_POST['msg']=='save'){
        // echo "<div class='alert alert-success'>Record was saved.</div>"; 
        // }
        ?>

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
                <?php $price_validate = !preg_match("/[.]/", $row['price']) ? $row['price'].".00" : $row['price'] ; ?>
                <td><?php echo htmlspecialchars($price_validate, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Promotion Price</td>
                <?php $p_price_validation = !preg_match("/[.]/", $row['promotionprice']) ? $row['promotionprice'].".00" : $row['promotionprice'] ; ?>
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
                    echo "<a href='product_update.php?id=$id' class='btn btn-primary'>Edit</a>"
                    ?>
                    <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                </td>
            </tr>
        </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>