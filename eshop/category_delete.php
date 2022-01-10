<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

try {

$query1 = "SELECT categorys.c_id, products.category_id FROM categorys INNER JOIN products ON categorys.c_id = products.category_id WHERE c_id = :c_id";
$stmt = $con->prepare($query1);
$stmt->bindParam(":c_id", $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$num = $stmt->rowCount();

if ($num > 0){
    echo "<script>alert('The Category that product is using');</script>";
    echo "<script>window.location.assign('category_read.php')</script>";
}else{
    $qdelete = "DELETE FROM categorys WHERE c_id = :c_id";
    $stmt = $con->prepare($qdelete);
    $stmt->bindParam(":c_id", $id);
    $stmt->execute();
    header("Location:category_read.php?msg=delete");
}
        
}

catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}


?>