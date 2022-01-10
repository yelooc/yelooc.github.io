<?php
include 'session_login.php';
include 'config/database.php';
include 'nav.php';
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Create New Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="page-header row">
            <h1 class="col">Create New Order</h1>
        </div>

        <?php
        include 'config/database.php';

        $query = "SELECT * FROM products ORDER BY p_id DESC";
        $stmt1 = $con->prepare($query);
        $stmt1->execute();

        $product_arrID = array();
        $product_arrName = array();

        while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {

            extract($row);
            array_push($product_arrID, $row['p_id']);
            array_push($product_arrName, $row['name']);
        }

        $query_customer = "SELECT * FROM customers ORDER BY username DESC";
        $stmt_customer = $con->prepare($query_customer);
        $stmt_customer->execute();

        if ($_POST) {

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
            if (empty($_POST['customer_username'])) {
                $flag = 1;
                $message = 'Please select Username.';
            }

            try {

                $query = "INSERT INTO order_summary SET customer_username=:customer_username, purchase_date=:purchase_date";

                $stmt = $con->prepare($query);
                $customer_username = $_POST['customer_username'];

                $stmt->bindParam(':customer_username', $customer_username);
                $purchase_date = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':purchase_date', $purchase_date);

                if ($flag == 0) {
                    if ($stmt->execute()) {

                        $last_id = $con->lastInsertid();
                        for ($count = 0; $count < count($_POST['product']); $count++) {

                            $query2 = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";
                            $stmt = $con->prepare($query2);
                            $stmt->bindParam('order_id', $last_id);
                            $stmt->bindParam('product_id', $_POST['product'][$count]);
                            $stmt->bindParam('quantity', $_POST['quantity'][$count]);
                            if (!empty($_POST['product'][$count]) && !empty($_POST['quantity'][$count])) {
                                $stmt->execute();
                            }
                        }
                        // echo "<div class='alert alert-success'>Record was saved. Last inserted ID is : $last_id</div>";
                        
                        header("Location:neworder_read_one.php?id=$last_id&msg=orderCreate_success");
                    } else {

                        $message = "Unable to save record";
                    }
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo $message;
                    echo "</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">

            <table class='table table-hover table-responsive table-bordered m-0'>

                <tr>
                    <td class="col-5">Customer's Username</td>
                    <td>
                        <div class="input-group mb-3">
                            <select class="form-select form-select fs-6 rounded" name="customer_username">
                                <option value="">Username</option>
                                <?php
                                while ($row = $stmt_customer->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                    $selected_username = $row['username'] == $_POST['customer_username'] ? 'selected' : '';
                                    echo "<option class='bg-white' value='{$username}'$selected_username>$username</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>

            <?php
            $array = array('');
            if ($_POST) {
                for ($y = 0; $y <= count($_POST['product']); $y++) {
                    if (empty($_POST['product'][$y]) && empty($_POST['quantity'][$y])) {

                        unset($_POST['product'][$y]);
                        unset($_POST['quantity'][$y]);
                    }
                    if (count($_POST['product']) != count(array_unique($_POST['product']))) {

                        unset($_POST['product'][$y]);
                        unset($_POST['quantity'][$y]);
                    }
                }
                if (count($_POST['product']) == 0) {
                    $array = array('');
                } else {
                    $array = $_POST['product'];
                }
            }
            foreach ($array as $product_row => $product_ID) {
            ?>
                <table class='table table-hover table-responsive table-bordered m-0' id='order_table'>
                    <tr class="productRow">
                        <td class='col-5'>Product</td>
                        <td class='col-3'>
                            <select class="form-control form-select fs-6 rounded" name="product[]">
                                <option value="">Product List</option>
                                <?php
                                $product_list = $_POST ? $_POST['product'] : ' ';
                                for ($pcount = 0; $pcount < count($product_arrName); $pcount++) {
                                    $selected_product = $product_arrID[$pcount] == $product_list[$product_row] ? 'selected' : '';
                                    echo "<option value='" . $product_arrID[$pcount] . "'$selected_product>" . $product_arrName[$pcount] . "</option>";
                                }
                                ?>
                            </select>

                        <td>
                            <select class="form-control form-select" name="quantity[]">
                                <option value="">Please Select Your Quantity</option>
                                <?php
                                for ($quantity = 1; $quantity <= 5; $quantity++) {
                                    $selected_quantity = $quantity == $_POST['quantity'][$product_row] ? 'selected' : '';
                                    echo "<option value='$quantity' $selected_quantity>$quantity</option>";
                                }
                                ?>
                        </td>
                        </td>
                        <td class="d-flex justify-content-center">
                            <button type="button" class="btn btn-danger" onclick="deleteMe(this)">X</button>
                        </td>
                    </tr>
                </table>

            <?php
            }
            ?>
            <div class="row">
                <div class="col">
                    <button type="button" class="add_one btn btn-primary">Add More Product</button>
                    <button type="button" class="delete_one btn btn-danger">Delete Last Product</button>
                </div>
                <div class="col text-end">
                    <input type='submit' value='Save' class='btn btn-primary' />
                    <?php
                    echo "<a href='neworder_read.php' class='btn btn-danger'>Back to Order Listing</a>";
                    ?>
                </div>
            </div>
            <div class="d-flex justify-content-center flex-column flex-lg-row">
                <div class="d-flex justify-content-center">
                </div>
            </div>

        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
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

        function deleteMe(row) {
            var table = document.getElementById('order_table')
            var allrows = table.getElementsByTagName('tr');
            if (allrows.length == 1) {
                alert("You are not allowed to delete.");
            } else {
                if (confirm("Confirm to delete?")) {
                    row.parentNode.parentNode.remove();
                }
            }
        }
    </script>

</body>

</html>