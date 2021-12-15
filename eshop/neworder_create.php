<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container-fuild bg-dark">
        <div class="container">

            <nav class="navbar-expand-lg py-2">

                <div class="collapse navbar-collapse d-flex justify-content-between">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="home.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-secondary" href="#" role="button" data-bs-toggle="dropdown">
                                Product
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="product_create.php">Create Product</a></li>
                                <li><a class="dropdown-item" href="product_read.php">Product Listing</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-secondary" href="#" role="button" data-bs-toggle="dropdown">
                                Customer
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="customer_create.php">Create Customer</a></li>
                                <li><a class="dropdown-item" href="customer_read.php">Customer Listing</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active text-white" href="#" role="button" data-bs-toggle="dropdown">
                                Order
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item bg-secondary" href="#">Create New Order</a></li>
                                <li><a class="dropdown-item" href="neworder_read.php">Order Listing</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="session_logout.php">Log Out</a>
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


        include 'config/database.php';

        // delete message prompt will be here

        // select all data
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
                // insert query
                $query = "INSERT INTO order_summary SET customer_username=:customer_username, purchase_date=:purchase_date";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $customer_username = $_POST['customer_username'];
                // bind the parameters
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
                    <td>Customer's Username</td>
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

                <?php
                // if ($_POST){ 
                // $post_product = count($_POST['product']);

                // }else{
                //     $post_product = 1;
                // }
                //算有多少个product row当submit过后
                // $post_product = $_POST ? count($_POST['product']) : 1;

                //result save data after
                // for ($product_row = 0; $product_row < $post_product; $product_row++) {
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
                    <tr class="productRow">
                        <td>Product</td>
                        <td>
                            <select class="form-control form-select fs-6 rounded" name="product[]">
                                <option value="">Product List</option>
                                <?php
                                $product_list = $_POST ? $_POST['product'] : ' ';
                                for ($pcount = 0; $pcount < count($product_arrName); $pcount++) {
                                    //第几个value ID是selected        //第几个product row
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
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td>
                        <button type="button" class="add_one btn btn-primary">Add More Product</button>
                        <button type="button" class="delete_one btn btn-danger">Delete Last Product</button>
                    </td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='neworder_read.php' class='btn btn-danger'>Back to read order</a>
                    </td>
                </tr>
                <div class="d-flex justify-content-center flex-column flex-lg-row">
                    <div class="d-flex justify-content-center">


                    </div>
                </div>
            </table>
        </form>

    </div> <!-- end .container -->
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
    </script>
    <!-- confirm delete record will be here -->

</body>

</html>