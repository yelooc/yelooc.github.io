<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

try {

    $order_details_delete = "DELETE FROM order_details WHERE order_id = :order_id";

            $stmt1 = $con->prepare($order_details_delete);
            $stmt1->bindParam(":order_id", $id);
            $stmt1->execute();

            $order_summary_delete = "DELETE FROM order_summary WHERE order_id = :order_id";
            $stmt2 = $con->prepare($order_summary_delete);
            $stmt2->bindParam(":order_id", $id);
            $stmt2->execute();
        
            
}

catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Order Delete SuccessFully</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<body>
    <div class="text-center border border-secondary p-5" style="margin:200px">

        <h5>Order Delete SuccessFully</h5>
        <div>Order's ID is : <?php echo htmlspecialchars($id, ENT_QUOTES);  ?><br>
        <?php
        echo "<a href='neworder_read.php'><button class='btn btn-primary'>OK</button></a>"
        ?>
      
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</html>