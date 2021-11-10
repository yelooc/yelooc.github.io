<?php
include 'config/database.php';

// delete message prompt will be here

// select all data
$query = "SELECT * FROM products ORDER BY id DESC";
$query_customer = "SELECT * FROM customers ORDER BY username DESC";
$stmt1 = $con->prepare($query);
$stmt2 = $con->prepare($query);
$stmt3 = $con->prepare($query);
$stmt_customer = $con->prepare($query_customer);

$stmt1->execute();
$stmt2->execute();
$stmt3->execute();
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
        var ret1 = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
        var ret2 = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
        var ret3 = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
        document.getElementById("box1").style.display = ret1 ? "" : "inline";
        document.getElementById("box2").style.display = ret2 ? "" : "inline";
        document.getElementById("box3").style.display = ret3 ? "" : "inline";
        return ret1;
        return ret2;
        return ret3;
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

            $quantity1 = "SELECT id, name, price FROM products WHERE name='" . $_POST['product_name1'] . "'";
            $stmt = $con->prepare($quantity1);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $product_price1 = $row['price'];
            }

            $quantity2 = "SELECT id, name, price FROM products WHERE name='" . $_POST['product_name2'] . "'";
            $stmt = $con->prepare($quantity2);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $product_price2 = $row['price'];
            }

            $quantity3 = "SELECT id, name, price FROM products WHERE name='" . $_POST['product_name3'] . "'";
            $stmt = $con->prepare($quantity3);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $product_price3 = $row['price'];
            }

            try {
                $product_name1 = $_POST['product_name1'];
                $quantity1 = $_POST['quantity1'];
                $subtotal1 = $product_price1 * $_POST['quantity1'];
                $product_name2 = $_POST['product_name2'];
                $quantity2 = $_POST['quantity2'];
                $subtotal2 = $product_price2 * $_POST['quantity2'];
                $product_name3 = $_POST['product_name3'];
                $quantity3 = $_POST['quantity3'];
                $subtotal3 = $product_price3 * $_POST['quantity3'];
                // insert query
                $order_details = "INSERT INTO order_details SET product_name1=:product_name1, quantity1=:quantity1, product_price1=:product_price1, product_name2=:product_name2, quantity2=:quantity2, product_price2=:product_price2,product_name3=:product_name3, quantity3=:quantity3, product_price3=:product_price3";
                // prepare query for execution
                $stmt = $con->prepare($order_details);

                // bind the parameters
                $stmt->bindParam(':product_name1', $product_name1);
                $stmt->bindParam(':quantity1', $quantity1);
                $stmt->bindParam(':product_price1', $product_price1);
                $stmt->bindParam(':product_name2', $product_name2);
                $stmt->bindParam(':quantity2', $quantity2);
                $stmt->bindParam(':product_price2', $product_price2);
                $stmt->bindParam(':product_name3', $product_name3);
                $stmt->bindParam(':quantity3', $quantity3);
                $stmt->bindParam(':product_price3', $product_price3);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }

            try {
                $customer_name = $_POST['customer_name'];
                $total_amount = $subtotal1 + $subtotal2 + $subtotal3;
                $created = date('Y-m-d H:i:s');

                // insert query
                $order_summary = "INSERT INTO order_summary SET customer_name=:customer_name, total_amount=:total_amount, created=:created";
                // prepare query for execution
                $stmt = $con->prepare($order_summary);

                // bind the parameters
                $stmt->bindParam(':customer_name', $customer_name);
                $stmt->bindParam(':total_amount', $total_amount);
                $stmt->bindParam(':created', $created);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
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
                            <select class="form-control fs-6 rounded" name="customer_name">
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
                <tr>
                    <td>Order 1</td>
                    <td>
                        <select class="form-control fs-6 rounded" name="product_name1">
                            <option class='bg-white' value="0.00" selected></option>
                            <?php
                            while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                                extract($row1);
                                echo "<option class='bg-white' value='" . $row1['name'] . "'>" . $row1['name'] . "</option>";
                            }
                            ?>
                        </select>
                        <div class="row">
                            <div class="col">
                                <button type="button" id="decrease1" class="btn btn-primary btn btn-lg" disabled onclick="minus1()">-</button>
                                <input type="text" name="quantity1" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" class="col-1 btn btn-lg border border-dark" id="box1" value="1" />
                                <button type="button" id="increase1" class="btn btn-primary btn btn-lg" onclick="plus1()">+</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Order 2</td>
                    <td>
                        <select class="form-control fs-6 rounded" name="product_name2">
                            <option class='bg-white' selected></option>
                            <?php

                            while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                extract($row2);
                                echo "<option class='bg-white' value='" . $row2['name'] . "'>" . $row2['name'] . "</option>";
                            }

                            ?>
                        </select>
                        <div class="row">
                            <div class="col">
                                <button type="button" id="decrease2" class="btn btn-primary btn btn-lg" disabled onclick="minus2()">-</button>
                                <input type="text" name="quantity2" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" class="col-1 btn btn-lg border border-dark" id="box2" value="1" />
                                <button type="button" id="increase2" class="btn btn-primary btn btn-lg" onclick="plus2()">+</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Order 3</td>
                    <td>
                        <select class="form-control fs-6 rounded" name="product_name3">
                            <option class='bg-white' selected></option>
                            <?php

                            while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                                extract($row3);
                                echo "<option class='bg-white' value='" . $row3['name'] . "'>" . $row3['name'] . "</option>";
                            }
                            ?>
                        </select>
                        <div class="row">
                            <div class="col">
                                <button type="button" id="decrease3" class="btn btn-primary btn btn-lg" disabled onclick="minus3()">-</button>
                                <input type="text" name="quantity3" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" class="col-1 btn btn-lg border border-dark" id="box3" value="1" />
                                <button type="button" id="increase3" class="btn btn-primary btn btn-lg" onclick="plus3()">+</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Order' class='btn btn-primary' />
                        <!-- <a href='product_read.php' class='btn btn-danger'>Back to read order</a> -->
                    </td>
                </tr>

            </table>
        </form>

    </div> <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <!-- confirm delete record will be here -->

</body>

</html>