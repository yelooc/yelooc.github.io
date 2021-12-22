
<?php 
include 'session_login.php';
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Eshop Customer Create to insert the data in database(PDO Method)</title>
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
                            <a class="nav-link dropdown-toggle active text-white" href="#" role="button" data-bs-toggle="dropdown">
                                Product
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item bg-secondary" href="#">Create Product</a></li>
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
                            <a class="nav-link dropdown-toggle text-secondary" href="#" role="button" data-bs-toggle="dropdown">
                                Order
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="neworder_create.php">Create New Order</a></li>
                                <li><a class="dropdown-item" href="neworder_read.php">Order Listing</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="contact_us.php">Contact us</a>
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

        <div class="page-header">
            <h1>Create Product</h1>
        </div>

        <?php
 include 'config/database.php';
        $query_category = "SELECT * FROM categorys ORDER BY id ASC";
        $stmt_category = $con->prepare($query_category);
        $stmt_category->execute();

        if ($_POST) {
           
            try {
               
                $query = "INSERT INTO products SET name=:name, description=:description, category_id=:category_id, price=:price, 
                promotionprice=:promotionprice,
                manufacturedate=:manufacturedate, 
                expireddate=:expireddate, 
                created=:created";
              
                $stmt = $con->prepare($query);
                $name = $_POST['name'];
                $description = $_POST['description'];
                $category_id = $_POST['category_id'];
                $price = $_POST['price'];
                $promotionprice = $_POST['promotionprice'];
                $manufacturedate = $_POST['manufacturedate'];
                $expireddate = $_POST['expireddate'];
               
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotionprice', $promotionprice);
                $stmt->bindParam(':manufacturedate', $manufacturedate);
                $stmt->bindParam(':expireddate', $expireddate);
                $created = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':created', $created);
            
                $flag = 0;
                $message = "";

                //empty validation
                if (!preg_match("/[a-zA-Z0-9]{1,}/", $expireddate)) {
                    $message = "Expired Date cannot be empty";
                    $flag = 1;
                }
                if (!preg_match("/[a-zA-Z0-9]{1,}/", $manufacturedate)) {
                    $message = "Manufacture Date cannot be empty";
                    $flag = 1;
                }
                if (!preg_match("/[a-zA-Z0-9]{1,}/", $promotionprice)) {
                    $message = "Promotion Price cannot be empty";
                    $flag = 1;
                } else {
                    //promotion price validation
                    if (preg_match("/-/", $promotionprice)) {
                        $message = "Promotion Price should not negative";
                        $flag = 1;
                    }
                    if ($promotionprice > $price) {
                        $message = "Promotion Price cannot more than Regular Price";
                        $flag = 1;
                    }
                    if (!preg_match("/[0-9]/", $promotionprice)) {
                        $message = "Promotion Price should be numbers only";
                        $flag = 1;
                    }
                    if (preg_match("/\s/", $promotionprice)) {
                        $message = "Promotion Price cannot contain space";
                        $flag = 1;
                    }
                }
                if (!preg_match("/[0-9]{1,}/", $price)) {
                    $message = "Price cannot be empty";
                    $flag = 1;
                } else {
                    //price validation
                    if (preg_match("/-/", $price) || $price < 1000) {
                        $message = "Price should not negative and less than 1000";
                        $flag = 1;
                    }
                    if (!preg_match("/[0-9]/", $price) || preg_match("/[a-zA-Z]/", $price)) {
                        $message = "Price should be numbers only";
                        $flag = 1;
                    }
                    if (preg_match("/\s/", $price)) {
                        $message = "price cannot contain space";
                        $flag = 1;
                    }
                }
                if (empty($category_id)){
                    $message = "Category cannot be empty";
                    $flag = 1;
                }
                if (!preg_match("/[a-zA-Z0-9]{1,}/", $description)) {
                    $message = "Description cannot be empty";
                    $flag = 1;
                }
                if (!preg_match("/[a-zA-Z0-9]{1,}/", $name)) {
                    $message = "Name cannot be empty";
                    $flag = 1;
                }

                //Date validation
                if ($expireddate . substr(0, 4) < $manufacturedate . substr(0, 4)) {
                    $message = "Manufacture date should be earlier than Expired date";
                    $flag = 1;
                    if ($expireddate . substr(5, 7) <  $manufacturedate . substr(5, 7)) {
                        $message = "Manufacture date should be earlier than Expired date";
                        $flag = 1;
                        if ($expireddate . substr(8, 10) <  $manufacturedate . substr(8, 10)) {
                            $message = "Manufacture date should be earlier than Expired date";
                            $flag = 1;
                        }
                    }
                }

                if ($flag == 0) {
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
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

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name="description" rows="5" class="form-control"></textarea></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <select class="form-control form-select fs-6 rounded" name="category_id">
                            <option value="">--Category--</option>
                            <?php
                            while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $selected_category = $row['id'] == $_POST['category_id'] ? 'selected' : '';
                                echo "<option class='bg-white' value='{$id}'$selected_category>$category_name</option>";
                            }
                            ?>
                    </td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotionprice' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacturedate' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expireddate' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>