<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

try {

    $order_details_delete = "DELETE FROM order_details WHERE order_id = :order_id";

            $stmt1 = $con->prepare($order_details_delete);
            $stmt1->bindParam(":order_id", $id);
            $stmt1->execute();

            $order_summary_delete = "DELETE FROM order_summary WHERE order_id = :order_id";
            $stmt2 = $con->prepare($order_summary_delete);
            $stmt2->bindParam(":order_id", $id);
            $stmt2->execute();
            header("Location:neworder_read.php?msg=delete");
        
            
}

catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>
