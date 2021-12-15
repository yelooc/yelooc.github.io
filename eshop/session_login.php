<?php

session_start();
if(!isset($_SESSION['correct_username'])){
    header('Location:login.php?msg=pleaselogin');
}

?>