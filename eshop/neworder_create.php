<?php
include 'config/database.php';

// delete message prompt will be here

// select all data
$query = "SELECT * FROM products ORDER BY product_id DESC";
$stmt = $con->prepare($query);
$stmt->execute();

$query_customer = "SELECT * FROM customers ORDER BY username DESC";
$stmt_customer = $con->prepare($query_customer);
$stmt_customer->execute();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<script>
    function plus1() {
        var a = document.getElementById("box1").value;
        document.getElementById("box1").value = parseInt(a) + 1;
        if (a == 1) {
            document.getElementById("decrease1").disabled = false;
        }
    }

    function minus1() {
        var a = document.getElementById("box1").value;
        document.getElementById("box1").value = parseInt(a) - 1;
        if (a == 2 || a == 1) {
            document.getElementById("decrease1").disabled = true;
        }
    }

    function plus2() {
        var a = document.getElementById("box2").value;
        document.getElementById("box2").value = parseInt(a) + 1;
        if (a == 1) {
            document.getElementById("decrease2").disabled = false;
        }
    }

    function minus2() {
        var a = document.getElementById("box2").value;
        document.getElementById("box2").value = parseInt(a) - 1;
        if (a == 2 || a == 1) {
            document.getElementById("decrease2").disabled = true;
        }
    }

    function plus3() {
        var a = document.getElementById("box3").value;
        document.getElementById("box3").value = parseInt(a) + 1;
        if (a == 1) {
            document.getElementById("decrease3").disabled = false;
        }
    }

    function minus3() {
        var a = document.getElementById("box3").value;
        document.getElementById("box3").value = parseInt(a) - 1;
        if (a == 2 || a == 1) {
            document.getElementById("decrease3").disabled = true;
        }
    }

    var specialKeys = new Array();
    specialKeys.push(8); //Backspace
    function IsNumeric(e) {
        var keyCode = e.which ? e.which : e.keyCode
        var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
        document.getElementById("box1").style.display = ret ? "" : "inline";
        return ret;
    }
</script>

<body>

    <div class="container-fuild bg-dark">
        <div class="container">

            <nav class="navbar-expand-lg py-2">

                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="product_create.php">Create Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="customer_create.php">Create Customer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="neworder_create.php">Create New Order</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-secondary" href="contact_us.php">Contact us</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="container">

        <div class="page-header row">
            <h1 class="col">Create New Order</h1>
        </div>

        <?php

        if ($_POST) {

            $flag = 0;
            $message = '';

            if ($_POST['product_name'][0] == 0) {

                $flag = 1;
                $message = 'Please select first order or if no product in product list please create one.';
            }else{
                for ($x=0;$x<=2;$x++){
                if (substr($_POST['quantity'][$x], 0, 1) == 0) {

                    $flag = 1;
                    $message = 'The quantity must more than 0.';
                }
            }
        }

            if (empty($_POST['customer_username'])) {

                $flag = 1;
                $message = 'Please select customer or if no customer please create one.';
            }

            try {
                // insert query
                $query = "INSERT INTO order_summary SET customer_username=:customer_username, purchase_date=:purchase_date";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $customer_username = $_POST['customer_username'];
                // bind the parameters
                $stmt->bindParam(':customer_username', $customer_username);
                // $total_amount = $_POST['quantity'][$count] * $_POST['total_amount'];
                $purchase_date = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':purchase_date', $purchase_date);

                if ($flag == 0) {
                    if ($stmt->execute()) {

                        $last_id = $con->lastInsertid();
                        for ($count = 0; $count < 100; $count++) {

                            $query2 = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";
                            $stmt = $con->prepare($query2);
                            $stmt->bindParam('order_id', $last_id);
                            $stmt->bindParam('product_id', $_POST['product_name'][$count]);
                            $stmt->bindParam('quantity', $_POST['quantity'][$count]);
                            if (!empty($_POST['product_name'][$count]) && !empty($_POST['quantity'][$count])){
                                $stmt->execute();  
                            }
                        }
                        echo "<div class='alert alert-success'>Record was saved. Last inserted ID is : $last_id</div>";
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


        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <td>Name</td>
                    <td>
                        <div class="input-group mb-3">
                            <select class="form-control fs-6 rounded" name="customer_username">
                                <option class='bg-white' selected></option>
                                <?php
                                while ($row_name = $stmt_customer->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row_name);
                                    echo "<option class='bg-white' value='{$username}'>$username</option>";
                                }
                                ?>
                            </select>

                        </div>

                    </td>
                </tr>

                <?php
                $product_arrID = array();
                $product_arrName = array();
                $product_arrPrice = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    array_push($product_arrID, $row['product_id']);
                    array_push($product_arrName, $row['name']);
                }



                for ($x = 0; $x <= 2; $x++) {
                ?>
                    <tr>
                        <td>Order <?php echo $x + (1); ?></td>
                        <td>
                            <select class="form-control fs-6 rounded" name="product_name[]">
                                <option class='bg-white' value="0">Product List</option>
                                <?php
                                for ($pcount = 0; $pcount < count($product_arrName); $pcount++) {
                                    echo "<option class='bg-white' value='" . $product_arrID[$pcount] . "'>" . $product_arrName[$pcount] . "</option>";
                                }
                                ?>
                            </select>
                            <div class="row">
                                <div class="col">
                                    <button type="button" id="decrease<?php echo $x + (1); ?>" class="btn btn-primary btn btn-lg" disabled onclick="minus<?php echo $x + (1) ?>()">-</button>

                                    <input type="text" name="quantity[]" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" class="col-2 btn btn-lg border border-dark" id="box<?php echo $x + (1); ?>" value="1" />

                                    <button type="button" id="increase<?php echo $x + (1); ?>" class="btn btn-primary btn btn-lg" onclick="plus<?php echo $x + (1) ?>()">+</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Order' class='btn btn-primary' />
                        <a href='neworder_read.php' class='btn btn-danger'>Back to read order</a>
                    </td>
                </tr>

            </table>
        </form>

    </div> <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <!-- confirm delete record will be here -->

</body>

</html>