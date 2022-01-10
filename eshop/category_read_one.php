<?php
include 'session_login.php';
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
include 'config/database.php';
include 'nav.php';

try {

    $query = "SELECT * FROM categorys WHERE c_id = :c_id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(":c_id", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo htmlspecialchars($category_name, ENT_QUOTES);  ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1><?php echo htmlspecialchars($category_name, ENT_QUOTES);  ?></h1>
        </div>
        <?php
    if (isset($_GET['msg']) && $_GET['msg'] == 'categoryCreate_success') {
        echo "<div class='alert alert-success mt-4'>Category Created Successfully.</div>";
    }
    if (isset($_GET['msg']) && $_GET['msg'] == 'categoryUpdate_success') {
        echo "<div class='alert alert-success mt-4'>Category Updated Successfully.</div>";
    }
    ?>
        <br>
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Category</td>
                <td><?php echo htmlspecialchars($category_name, ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php
                    echo "<a href='category_update.php?id=$id' class='btn btn-primary me-2'>Edit</a>";
                    echo "<a href='category_read.php' class='btn btn-danger'>Back to Category List</a>";
                    ?>
                </td>
            </tr>
        </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>