<?php 
session_start();
if(!isset($_SESSION['save'])){
    header('Location:product_read_one.php?msg=save');
}

?>