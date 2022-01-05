<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

try {

    $qdelete = "DELETE FROM products WHERE product_id = :product_id";

            $stmt = $con->prepare($qdelete);
            $stmt->bindParam(":product_id", $id);
            $stmt->execute();
            header("Location:product_read.php");
            
}

catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>
        