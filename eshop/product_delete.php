<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

try {

    $qdelete = "DELETE FROM products WHERE product_id = :product_id";

            $stmt = $con->prepare($qdelete);
            $stmt->bindParam(":product_id", $id);
            $stmt->execute();

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
            header("Location:product_read.php?id=$username");
            }
}

catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>
        