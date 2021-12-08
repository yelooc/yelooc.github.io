<?php
// get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

// read current record's data
try {
    // prepare select query
    $query = "SELECT * FROM products WHERE product_id = :id ";
    $stmt = $con->prepare($query);

    // Bind the parameter
    $stmt->bindParam(":id", $id);

    // execute our query
    $stmt->execute();

    // store retrieved row to a variable
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // values to fill up our form
    extract($row);
    // $name = $row['name'];
    // $description = $row['description'];
    // $price = $row['price'];
    // shorter way to do that is extract($row)
}

// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Update Product</h1>
        </div>

        <?php
// check if form was submitted
if ($_POST) {
    try {
        // write update query
        // in this case, it seemed like we have so many fields to pass and
        // it is better to label them and not use question marks
        $query = "UPDATE products
                  SET name=:name, description=:description,
   price=:price, promotionprice=:promotionprice, manufacturedate=:manufacturedate, expireddate=:expireddate WHERE product_id = :product_id";
        // prepare query for excecution
        $stmt = $con->prepare($query);
        // posted values
        $name = htmlspecialchars(strip_tags($_POST['name']));
        $description = htmlspecialchars(strip_tags($_POST['description']));
        $price = htmlspecialchars(strip_tags($_POST['price']));
        $promotionprice = htmlspecialchars(strip_tags($_POST['promotionprice']));
        $manufacturedate = htmlspecialchars(strip_tags($_POST['manufacturedate']));
        $expireddate = htmlspecialchars(strip_tags($_POST['expireddate']));
        // bind the parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':promotionprice', $promotionprice);
        $stmt->bindParam(':manufacturedate', $manufacturedate);
        $stmt->bindParam(':expireddate', $expireddate);
        $stmt->bindParam(':product_id', $id);
        // Execute the query
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
    // show errors
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
} 
?>

        <!--we have our html table here where the record will be displayed-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotionprice' value="<?php echo htmlspecialchars($promotionprice, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacturedate' class='form-control' value="<?php echo htmlspecialchars($manufacturedate, ENT_QUOTES);  ?>" /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expireddate' class='form-control' value="<?php echo htmlspecialchars($expireddate, ENT_QUOTES);  ?>" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div> <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>