<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

try {
    $query1 = "SELECT products.p_id, order_details.product_id FROM products INNER JOIN order_details ON products.p_id = order_details.product_id WHERE p_id = :p_id";
    $stmt = $con->prepare($query1);
    $stmt->bindParam(":p_id", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $num = $stmt->rowCount();

    if ($num > 0) {
        echo "<script>alert('The Product that Order is using');</script>";
        echo "<script>window.location.assign('product_read.php')</script>";
    } else {
        $qdelete = "DELETE FROM products WHERE p_id = :p_id";
        $stmt = $con->prepare($qdelete);
        $stmt->bindParam(":p_id", $id);
        $stmt->execute();
        header("Location:product_read.php?msg=delete");
    }
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
