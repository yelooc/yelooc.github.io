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

    echo "<div class='bg-dark'>";
    echo      "<div class='container'>";
    echo      "<nav class='navbar-expand-lg py-2'>";
    // echo "<button class='navbar-toggler navbar-dark' type='button' data-bs-toggle='collapse' data-bs-target='#navbarToggleExternalContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
    // <span class='navbar-toggler-icon'></span>
    // </button>";
    echo     "<div class='collapse navbar-collapse d-flex justify-content-between'>";
    echo          "<ul class='navbar-nav'>";
    echo          "<li class='nav-item'>";
    echo "<a class='nav-link text-secondary' href='home.php?id={$username}'>Home</a>";
    echo "</li>";
    echo "<li class='nav-item dropdown'>";
    echo "<a class='nav-link dropdown-toggle text-secondary' href='#' role='button' data-bs-toggle='dropdown'>";
    echo "Product";
    echo "</a>";
    echo "<ul class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>";
    echo "<li><a class='dropdown-item' href='product_create.php?id={$username}'>Create Product</a></li>";
    echo "<li><a class='dropdown-item' href='product_read.php?id={$username}'>Product Listing</a></li>";
    echo "</ul>";
    echo "</li>";
    echo "<li class='nav-item dropdown'>";
    echo "<a class='nav-link dropdown-toggle text-secondary' href='#' role='button' data-bs-toggle='dropdown'>";
    echo "Customer";
    echo "</a>";
    echo "<ul class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>";
    echo "<li><a class='dropdown-item' href='customer_create.php?id={$username}'>Create Customer</a></li>";
    echo "<li><a class='dropdown-item' href='customer_read.php?id={$username}'>Customer Listing</a></li>";
    echo "</ul>";
    echo "</li>";
    echo "<li class='nav-item dropdown'>";
    echo "<a class='nav-link dropdown-toggle text-secondary' href='#' role='button' data-bs-toggle='dropdown'>";
    echo "Order";
    echo "</a>";
    echo "<ul class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>";
    echo "<li><a class='dropdown-item' href='neworder_create.php?id={$username}'>Create New Order</a></li>";
    echo "<li><a class='dropdown-item' href='neworder_read.php?id={$username}'>Order Listing</a></li>";
    echo "</ul>";
    echo "</li>";
    echo "<li class='nav-item'>";
    echo "<a class='nav-link text-secondary' href='contact_us.php?id={$username}'>Contact us</a>";
    echo "</li>";
    echo "</ul>";
    echo "<ul class='navbar-nav'>";
    echo "<li class='nav-item'>";
    echo "<a class='nav-link text-secondary' href='customer_update.php?id={$username}'>$username</a>";
    echo "</li>";
    echo "<li class='nav-item'>";
    echo "<a class='nav-link text-secondary' href='session_logout.php?id={$id}'>Log Out</a>";
    echo "</li>";
    echo "</ul>";
    echo "</div>";
    echo "</nav>";
    echo "</div>";
    echo "</div>";
}
