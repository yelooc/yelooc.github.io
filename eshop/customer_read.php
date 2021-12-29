<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
include 'config/database.php';

include 'nav.php';

$query = "SELECT username, path, email, firstname, lastname, gender, date_of_birth FROM customers ORDER BY username ASC";
$stmt = $con->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Customer Read - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Read Customers</h1>
        </div>
        <br><br>
        <?php
        echo "<a href='customer_create.php?id={$id}' class='btn btn-primary'>Create New Customer</a>";
        ?>
        <br><br>
        <table class='table table-hover table-responsive table-bordered'>

            <tr>
                <th>Username</th>
                <th>Customer Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Date Of Birth</th>
                <th>Action</th>
            </tr>

            <?php

            if ($num > 0) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    
                    echo "<tr>";
                    echo "<td>{$username}</td>";
                    echo "<td class='text-center'><img src='$path' width='100px'></td>";
                    echo "<td>{$firstname} {$lastname}</td>";
                    echo "<td>{$email}</td>";
                    echo "<td>{$gender}</td>";
                    echo "<td>{$date_of_birth}</td>";
                    echo "<td class='d-flex justify-content-between'>";
                    echo "<a href='customer_read_one.php?id={$username}' class='btn btn-info m-r-1em'>Read</a>";
                    echo "<a href='customer_update.php?id={$username}' class='btn btn-primary m-r-1em'>Edit</a>";
                    echo "<a href='customer_delete.php?id={$username}'  class='btn btn-danger'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }

            echo "</table>";

            ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>