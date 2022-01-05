<?php

session_start();
if(!isset($_SESSION['correct_username'])){
    header('Location:index.php?msg=pleaselogin');
}

?>