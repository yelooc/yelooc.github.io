<?php
include 'session_login.php';
include 'config/database.php';

$query_lastorder = "SELECT * FROM order_summary ORDER BY order_id DESC LIMIT 1";
$stmt_lastorder = $con->prepare($query_lastorder);
$stmt_lastorder->execute();
$lastorder = $stmt_lastorder->rowCount();

?>

<!DOCTYPE HTML>
<html>

<head>
    <title>NewOrder Create SuccessFully</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="text-center border border-secondary p-5" style="margin:200px">

        <h5>Order Create SuccessFully</h5>
        <?php
        if ($lastorder > 0) {
            $row = $stmt_lastorder->fetch(PDO::FETCH_ASSOC);
        ?>
            <div>Last inserted ID is : <?php echo $row['order_id'] ?>
            </div>
        <?php
        }
        ?>
        <?php
        echo "<a href='neworder_read.php'><button class='btn btn-primary'>OK</button></a>"
        ?>

    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</html>