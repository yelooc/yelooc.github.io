<?php
include 'session_login.php';
include 'config/database.php';
include 'nav.php';

$query_category = "SELECT * FROM categorys ORDER BY c_id ASC";
$stmt_category = $con->prepare($query_category);
$stmt_category->execute();
$num = $stmt_category->rowCount();

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Category Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<body>
<div class="container">

<div class="page-header">
    <h1>Category Listing</h1>
</div>
<?php
        if (isset($_GET['msg']) && $_GET['msg'] == 'delete') {
                echo "<div class='alert alert-success'>Delete Category Succesfully</div>";
            }
            ?>

<br>
        
       <a href='category_create.php' class='btn btn-primary'>Create New Category</a>
        
        <br><br>
        <table class='table table-hover table-responsive table-bordered'>

            <tr>
            <th>Category's ID</th>
                <th>Category's Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>

            <?php

            if ($num > 0) {

                while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    
                    echo "<tr>";
                    echo "<td>{$c_id}</td>";
                    echo "<td>{$category_name}</td>";
                    echo "<td>{$description}</td>";
                    echo "<td class='d-flex justify-content-around'>";
                    echo "<a href='category_read_one.php?id={$c_id}' class='btn btn-info'>Read</a>";
                    echo "<a href='category_update.php?id={$c_id}' class='btn btn-primary'>Edit</a>";
                    echo "<button onclick='myFunction_category({$c_id})' name='delete' class='btn btn-danger'>Delete</button>";
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
function myFunction_category(c_id) {
    
    let text = "Do you sure want ot delete?";
    if (confirm(text) == true) {
        window.location = "category_delete.php?id=" +c_id;
    }else{

    }
}
</script>

</html>
