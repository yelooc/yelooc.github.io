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
    $username = $row['username'];
    $path = $row['path'];
}
?>
<div class='container-fuild bg-dark'>
    <nav class='navbar navbar-expand-lg py-2 navbar-dark container-sm'>
    <button class="navbar-toggler mx-2 bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#bdNavbar" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
        <div class="navbar-collapse collapse row mx-2" id="bdNavbar">
            <ul class='navbar-nav col'>
                <a class='nav-link text-secondary' href='home.php'>Home</a>
                </li>
                <li class='nav-item dropdown'>
                    <a class='nav-link dropdown-toggle text-secondary' href='#' role='button' data-bs-toggle='dropdown'>
                        Product
                    </a>
                    <ul class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>
                        <li><a class='dropdown-item' href='product_read.php'>Product Listing</a></li>
                        <li><a class='dropdown-item' href='product_create.php'>Product Create</a></li>
                        <li><a class='dropdown-item' href='category_read.php'>Category Listing</a></li>
                        <li><a class='dropdown-item' href='category_create.php'>Category Create</a></li>
                    </ul>
                </li>
                <li class='nav-item dropdown'>
                    <a class='nav-link dropdown-toggle text-secondary' href='#' role='button' data-bs-toggle='dropdown'>
                        Customer
                    </a>
                    <ul class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>
                        <li><a class='dropdown-item' href='customer_read.php'>Customer Listing</a></li>
                        <li><a class='dropdown-item' href='customer_create.php'>Create Customer</a></li>
                    </ul>
                </li>
                <li class='nav-item dropdown'>
                    <a class='nav-link dropdown-toggle text-secondary' href='#' role='button' data-bs-toggle='dropdown'>
                        Order
                    </a>
                    <ul class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>
                        <li><a class='dropdown-item' href='neworder_read.php'>Order Listing</a></li>
                        <li><a class='dropdown-item' href='neworder_create.php'>Create New Order</a></li>
                    </ul>
                </li>
            </ul>
            <ul class='navbar-nav col text-end d-flex justify-content-end'>
                <li class='nav-item'>
                    <?php
                    echo "<a class='nav-link text-secondary' href='customer_update.php?id={$username}'>$username<img src='$path' class='ms-2' style='border-radius: 50%;width:30px;height:30px;object-fit: cover'></a>";
                    ?>
                </li>
                <br>
                <li class='nav-item'>
                    <a class='nav-link text-secondary' href='session_logout.php'>Log Out</a>
                </li>
            </ul>
        </div>
    </nav>
</div>