<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

try {
    $query1 = "SELECT customers.username, order_summary.customer_username FROM customers INNER JOIN order_summary ON customers.username = order_summary.customer_username WHERE username = :username";
    $stmt = $con->prepare($query1);
    $stmt->bindParam(":username", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $num = $stmt->rowCount();

    if ($num > 0) {
        echo "<script>alert('The Customer that Order is using');</script>";
        echo "<script>window.location.assign('customer_read.php')</script>";
    } else {
        $qdelete = "DELETE FROM customers WHERE username = :username";
        $stmt = $con->prepare($qdelete);
        $stmt->bindParam(":username", $id);
        $stmt->execute();
        header("Location:customer_read.php?msg=delete");
    }
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}