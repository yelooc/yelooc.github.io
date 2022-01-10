<?php
include 'session_login.php';
include 'config/database.php';
include 'nav.php';

$query = "SELECT path, username, email, firstname, lastname, gender, date_of_birth FROM customers ORDER BY username ASC";
$stmt = $con->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Customer Listing</h1>
        </div>
        <?php
        if (isset($_GET['msg']) && $_GET['msg'] == 'delete') {
                echo "<div class='alert alert-success'>Delete Customer Succesfully</div>";
            }
            ?>
        <br>
        
        <a href='customer_create.php' class='btn btn-primary'>Create New Customer</a>
        
        <br><br>
        <table class='table table-hover table-responsive table-bordered'>

            <tr>
            <th>Image</th>
                <th>Username</th>
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
                    echo "<td class='text-center'><img src='" . $row['path'] . "' style='object-fit: cover;height:100px;width:100px;'></td>";
                    echo "<td>{$username}</td>";
                    echo "<td>{$firstname} {$lastname}</td>";
                    echo "<td>{$email}</td>";
                    echo "<td>{$gender}</td>";
                    echo "<td>{$date_of_birth}</td>";
                    echo "<td class='text-center'>";
                    echo "<a href='customer_read_one.php?id={$username}' class='btn btn-info me-2'>Read</a>";
                    echo "<a href='customer_update.php?id={$username}' class='btn btn-primary'>Edit</a>";
                    echo "<button onclick='myFunction_customer(\"{$username}\")' class='btn btn-danger ms-2'>Delete</button>";
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
<script>
function myFunction_customer(username) {
    
    let text = "Do you sure want ot delete?";
    if (confirm(text) == true) {
        window.location = "customer_delete.php?id=" +username;
    }else{

    }
}
</script>

</html>