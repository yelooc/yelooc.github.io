<?php
// get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

//include database connection
include 'config/database.php';

// read current record's data
try {

    $query1 = "SELECT order_details.order_details_id, order_details.order_id, order_details.product_id, order_details.quantity, products.name FROM order_details INNER JOIN products ON order_details.product_id = products.product_id WHERE order_id = :order_id ";

    $stmt = $con->prepare($query1);
    $stmt->bindParam(":order_id", $id);
    $stmt->execute();
    $num = $stmt->rowCount();

    $query2 = "SELECT order_summary.order_id, customers.username FROM order_summary INNER JOIN customers ON order_summary.customer_username = customers.username WHERE order_id=$id";

    $stmt2 = $con->prepare($query2);
    $stmt2->execute();

    // store retrieved row to a variable
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $cus_username = $row2['username'];
    $orderID = $row2['order_id'];

    $query_customer = "SELECT * FROM customers ORDER BY username DESC";
    $stmt_customer = $con->prepare($query_customer);
    $stmt_customer->execute();

    $query = "SELECT * FROM products ORDER BY product_id DESC";
    $stmt1 = $con->prepare($query);
    $stmt1->execute();

    $product_arrID = array();
    $product_arrName = array();

    while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {

        extract($row);
        array_push($product_arrID, $row['product_id']);
        array_push($product_arrName, $row['name']);
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>

<?php
// check if form was submitted
if ($_POST) {

    try {
        // write update query
        // in this case, it seemed like we have so many fields to pass and
        // it is better to label them and not use question marks
        $query_order_summary = "UPDATE order_summary
                  SET customer_username=:customer_username WHERE order_id = :order_id";
        $query_order_details = "UPDATE order_details
                  SET product_id=:product_id, quantity=:quantity WHERE order_id = :order_id";
        $stmt1 = $con->prepare($query_order_summary);
        $stmt2 = $con->prepare($query_order_details);
        $customer_username = $_POST['customer_username'];
        $product_id = $_POST['product_id'];
        $quan = $_POST['quantity'];
        $stmt1->bindParam(':customer_username', $customer_username);
        $stmt2->bindParam(':product_id', $product_id);
        $stmt2->bindParam(':quantity', $quan);
        $stmt1->bindParam(':order_id', $id);
        $stmt2->bindParam(':order_id', $id);

        if ($stmt1->execute()) {
            if ($stmt2->execute()){
                echo "<div class='alert alert-success'>Record was saved.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>";
            echo $message;
            echo "</div>";
        }
    }
    // show errors
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Read one order</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

</head>

<body>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Update Order Details</h1>
        </div>

        <?php
        echo "<br><h6>Order ID : $orderID</h6>"; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <?php
            echo "<br><h6>Customer's Username : <select name='customer_username' class='form-control'>";
            while ($row = $stmt_customer->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $username_selected = $row['username'] == $cus_username ? 'selected' : '';
                echo "<option value='$username' $username_selected>$username</option>";
            }
            echo "</select></h6>";

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>Product Name</th>";
            echo "<th>Quantity</th>";
            echo "</tr>";

            // retrieve our table contents
            if ($num > 0) {

                // creating new table row per record

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<tr>";
                    echo "<td><select class='form-control' name='product_id'>";
                    for ($pcount = 0; $pcount < count($product_arrName); $pcount++) {
                        $product_selected = $product_arrName[$pcount] == $name ? 'selected' : '';
                        echo "<option value='" . $product_arrID[$pcount] . "'$product_selected>" . $product_arrName[$pcount] . "</option>";
                    }
                    echo "</select></td>";
                    echo "<td><select class='form-select' name='quantity'>";
                    for ($quantity = 1; $quantity <= 5; $quantity++) {
                        $quantity_selected = $row['quantity'] == $quantity ? 'selected' : '';
                        echo "<option value='$quantity'$quantity_selected>$quantity</option>";
                    }
                    echo "</select></td>";
                    echo "</tr>";
                }
                // end table
                echo "</table>";
                echo "<div class='d-flex justify-content-center'>";
                echo "<input type='submit' value='Save Changes' class='btn btn-primary me-2'/>";
                echo "<a href='neworder_read.php' class='btn btn-danger'>Back to read Order</a>";
                echo "</div>";
            }

            ?>
        </form>

    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>