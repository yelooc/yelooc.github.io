<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

try {

    $query1 = "SELECT order_details.order_details_id, order_details.order_id, order_details.product_id, order_details.quantity, products.name FROM order_details INNER JOIN products ON order_details.product_id = products.product_id WHERE order_id = :order_id ";

    $stmt = $con->prepare($query1);
    $stmt->bindParam(":order_id", $id);
    $stmt->execute();
    $num = $stmt->rowCount();

    $query2 = "SELECT * FROM order_summary WHERE order_id=$id";
    $stmt1 = $con->prepare($query2);
    $stmt1->execute();

    $row = $stmt1->fetch(PDO::FETCH_ASSOC);
    $cus_username = $row['customer_username'];
    $orderID = $row['order_id'];

    $query = "SELECT * FROM products ORDER BY product_id DESC";
    $stmt2 = $con->prepare($query);
    $stmt2->execute();

    $product_arrID = array();
    $product_arrName = array();

    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {

        extract($row2);
        array_push($product_arrID, $row2['product_id']);
        array_push($product_arrName, $row2['name']);
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Order Update</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

</head>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Update Order Details</h1>
        </div>

        <?php

if ($_POST) {

    $qdelete = "DELETE FROM order_details WHERE order_id = :order_id";
    $stmt = $con->prepare($qdelete);
    $stmt->bindParam(":order_id", $id);

    try {

        $flag = 0;
        $callselect_at_least_flag = 0;
        $callselectquantity_flag = 0;
        $callselectproduct_flag = 0;
        $message = '';

        for ($count1 = 0; $count1 < count($_POST['product']); $count1++) {
            if (!empty($_POST['product'][$count1]) && !empty($_POST['quantity'][$count1])) {
                $callselect_at_least_flag++;
            }
            if (!empty($_POST['product'][$count1]) && empty($_POST['quantity'][$count1])) {
                $callselectquantity_flag++;
            }
            if (empty($_POST['product'][$count1]) && !empty($_POST['quantity'][$count1])) {
                $callselectproduct_flag++;
            }
        }

        if (count($_POST['product']) !== count(array_unique($_POST['product']))) {
            $flag = 1;
            $message = 'Duplicate product is not allowed.';
        }

        if ($callselect_at_least_flag < 1) {
            $flag = 1;
            $message = 'Please select the at least one product';
        }
        if ($callselectquantity_flag > 0) {
            $flag = 1;
            $message = 'please select quantity';
        }
        if ($callselectproduct_flag > 0) {
            $flag = 1;
            $message = 'Please select product';
        }

        if ($flag == 0) {
            if ($stmt->execute()) {
                for ($count = 0; $count < count($_POST['product']); $count++) {
                    $sql = 'INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity';
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':order_id', $id);
                    $stmt->bindParam(':product_id', $_POST['product'][$count]);
                    $stmt->bindParam(':quantity', $_POST['quantity'][$count]);

                    if (!empty($_POST['product'][$count]) && !empty($_POST['quantity'][$count])) {
                        $stmt->execute();
                        header("Location:neworder_read_one.php?id=" . $id);
                    }
                }
                echo "<div class='alert alert-success'>Record was saved.</div>";
            } else {
                $message .= 'Unable to save record';
            }
        } else {
            echo "<div class='alert alert-danger'>" . $message . "</div>";
        }
    }

    // show errors
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
}
?>

        <?php
        echo "<br><h6>Order ID : $orderID</h6>";
        echo "<br><h6>Customer's Username : $cus_username</h6>";
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <?php

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            echo "<tr>";
            echo "<th>Product Name</th>";
            echo "<th>Quantity</th>";
            echo "</tr>";
            $array = array('');

            if ($num > 0) {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    extract($row);
                    echo "<tr class='productRow'>";
                    echo "<td><select class='form-control' name='product[]'>";
                    echo "<option value=''>Product List</option>";

                    for ($pcount = 0; $pcount < count($product_arrName); $pcount++) {
                        $product_selected = $product_arrName[$pcount] == $name ? 'selected' : '';
                        $selected_product = $product_arrID[$pcount] == $product_list[$pcount] ? 'selected' : '';
                        $result_product = $_POST ? $selected_product : $product_selected;
                        echo "<option value='" . $product_arrID[$pcount] . "'$result_product>" . $product_arrName[$pcount] . "</option>";
                    }
                    echo "</select></td>";
                    echo "<td><select class='form-select' name='quantity[]'>";
                    echo "<option value=''>Please Select Your Quantity</option>";
                    for ($quantity = 1; $quantity <= 5; $quantity++) {
                        $quantity_selected = $row['quantity'] == $quantity ? 'selected' : '';
                        $selected_quantity = $quantity == $_POST['quantity'][$pcount] ? 'selected' : '';
                        $result_quantity = $_POST ? $selected_quantity : $quantity_selected;
                        echo "<option value='$quantity'$result_quantity>$quantity</option>";
                    }

                    echo "</select></td>";
                    echo "</tr>";
                }
              
                echo "<tr>";
                echo "<td>";
                echo "<button type='button' class='add_one btn btn-primary me-2'>Add More Product</button>";
                echo "<button type='button' class='delete_one btn btn-danger'>Delete Last Product</button>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "<div class='d-flex justify-content-center'>";
                echo "<input type='submit' value='Save Changes' class='btn btn-primary me-2'/>";
                echo "<a href='neworder_read.php' class='btn btn-danger'>Back to read Order</a>";
                echo "</div>";
            }

            ?>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>
<script>
    document.addEventListener('click', function(event) {
        if (event.target.matches('.add_one')) {
            var element = document.querySelector('.productRow');
            var clone = element.cloneNode(true);
            element.after(clone);
        }
        if (event.target.matches('.delete_one')) {
            var total = document.querySelectorAll('.productRow').length;
            if (total > 1) {
                var element = document.querySelector('.productRow');
                element.remove(element);
            }
        }
    }, false);
</script>

</html>