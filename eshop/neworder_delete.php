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
            header("Location:neworder_read.php?id=$username");
            }
}

catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>