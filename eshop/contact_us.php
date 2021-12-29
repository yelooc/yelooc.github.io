<!--ID : 2030346 -->
<!--Name : NG WING Chun -->
<!--Topic : Eshop Contact Us-->
<?php 
include 'session_login.php';
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
include 'config/database.php';

include 'nav.php';
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Eshop Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="p-5 container">
        <h1 class="fw-bold text-center">How Can We Help You?</h1>
        <br><br><br>
        <h5 class="fw-bold">Create Product</h5>
        <br><br>
        <p class="">First, you need to go to the page of Create Product to fill the information and to save your product.</p>
        <br><br>
        <h5 class="fw-bold">Create Customer</h5>
        <br><br>
        <p>First, you need to go to the page of Create Customer to fill the information and to save your customer information.</p>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>