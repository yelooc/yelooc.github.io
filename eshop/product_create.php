<?php
include 'session_login.php';
include 'config/database.php';
include 'nav.php';
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Create Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container">

        <div class="page-header">
            <h1>Create Product</h1>
        </div>

        <?php

        $query_category = "SELECT * FROM categorys ORDER BY c_id ASC";
        $stmt_category = $con->prepare($query_category);
        $stmt_category->execute();

        if ($_POST) {

            if (!empty($_FILES['fileToUpload']['name'])) {

                $query_img = 'SELECT * FROM products ORDER BY p_id DESC LIMIT 1';
                $stmt_img = $con->prepare($query_img);
                $stmt_img->execute();
                $row = $stmt_img->fetch(PDO::FETCH_ASSOC);

                $target_dir = "uploads/" . $row['p_id'];
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $isUploadOK = TRUE;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

                if ($check !== false) {
                    $isUploadOK = TRUE;
                } else {
                    $flag = 1;
                    $message .= "File is not an image.<br>";
                    $isUploadOK = FALSE;
                }

                if ($_FILES["fileToUpload"]["size"] > 5000000) {
                    $flag = 1;
                    $message .= "Sorry, your file is too large.<br>";
                    $isUploadOK = FALSE;
                }
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $flag = 1;
                    $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
                    $isUploadOK = FALSE;
                }

                if ($isUploadOK == TRUE) {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $last_id = $con->lastInsertid();
                        header("Location:product_read_one.php?id=$last_id&msg=productCreate_success");
                    }
                }
            } else {
                $target_file = "uploads/noimg_product.png";
            }

            try {

                $query = "INSERT INTO products SET name=:name, description=:description, category_id=:category_id, price=:price, 
                promotionprice=:promotionprice,
                path_img=:path_img,
                manufacturedate=:manufacturedate, 
                expireddate=:expireddate, 
                created=:created";

                $stmt = $con->prepare($query);
                $name = $_POST['name'];
                $description = $_POST['description'];
                $category_id = $_POST['category_id'];
                $price = $_POST['price'];
                $promotionprice = $_POST['promotionprice'];
                $path_img = $target_file;
                $manufacturedate = $_POST['manufacturedate'];
                $expireddate = $_POST['expireddate'];

                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotionprice', $promotionprice);
                $stmt->bindParam(':path_img', $path_img);
                $stmt->bindParam(':manufacturedate', $manufacturedate);
                $stmt->bindParam(':expireddate', $expireddate);
                $created = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':created', $created);

                $flag = 0;
                $message = "";

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

                if ($expireddate . substr(0, 10) == $manufacturedate . substr(0, 10)) {
                    $message = "Manufacture date cannot same with Expired date";
                    $flag = 1;
                }

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
                    if ($promotionprice == $price) {
                        $message = "Promotion Price cannot same with Regular Price";
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
                    if (preg_match("/-/", $price)) {
                        $message = "Price should not negative";
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
                if (preg_match("/[a-zA-Z]{1,}/", $price)) {
                    $message = "Price should be numbers only";
                    $flag = 1;
                }
                if (empty($category_id)) {
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

                if ($flag == 0) {
                    if ($stmt->execute()) {
                        $last_id = $con->lastInsertid();
                        header("Location:product_read_one.php?id=$last_id&msg=productCreate_success");
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

        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' value="<?php if ($_POST) {
                                                                                        echo $_POST['name'];
                                                                                    } ?>" /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name="description" rows="5" class="form-control"><?php if ($_POST) {
                                                                                        echo $_POST['description'];
                                                                                    } ?></textarea></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <select class="form-control form-select fs-6 rounded" name="category_id">
                            <option value="">--Category--</option>
                            <?php
                            while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $selected_category = $row['c_id'] == $_POST['category_id'] ? 'selected' : '';
                                echo "<option class='bg-white' value='{$c_id}'$selected_category>$category_name</option>";
                            }
                            ?>
                    </td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' value="<?php if ($_POST) {
                                                                                        echo $_POST['price'];
                                                                                    } ?>" /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotionprice' class='form-control' value="<?php if ($_POST) {
                                                                                                    echo $_POST['promotionprice'];
                                                                                                } ?>" /></td>
                </tr>
                <tr>
                    <td>
                        <p>Product Image</p>
                    </td>
                    <td><input type="file" name="fileToUpload" class="form-control" id="fileToUpload"></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacturedate' class='form-control' value="<?php if ($_POST) {
                                                                                                    echo $_POST['manufacturedate'];
                                                                                                } ?>" /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expireddate' class='form-control' value="<?php if ($_POST) {
                                                                                                echo $_POST['expireddate'];
                                                                                            } ?>" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <?php
                        echo "<a href='product_read.php' class='btn btn-danger'>Back to Product Listing</a>";
                        ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>