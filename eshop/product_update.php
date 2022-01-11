<?php
include 'session_login.php';
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
include 'config/database.php';
include 'nav.php';

try {

    $query = "SELECT * FROM products WHERE p_id = :p_id ";
    $stmt = $con->prepare($query);
    $stmt->bindParam(":p_id", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
}

// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title><?php echo htmlspecialchars($name, ENT_QUOTES);  ?> (Edit)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("#remove").click(function() {
            $("#image").attr("src", "uploads/noimg_product.png");
        });
    });
</script>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Update Product</h1>
        </div>

        <?php

        if (isset($_POST['remove_img'])) {
            echo "<div class='alert alert-danger'>The Image has been deleted</div>";
            if ($row['path_img'] != 'uploads/noimg_product.png') {
                if (file_exists($row['path_img'])) {
                    unlink($row['path_img']);
                }
            } else {
            }

            $target_file = "uploads/noimg_product.png";
            $query = "UPDATE products SET path_img=:path_img where p_id=:p_id";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':p_id', $id);
            $stmt->bindParam(':path_img', $target_file);
            $stmt->execute();
        }

        if (isset($_POST['save'])) {

            if (!empty($_FILES['fileToUpload']['name'])) {
                if ($row['path_img'] != 'uploads/noimg_product.png') {
                    unlink($row['path_img']);
                }

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
                        header("Location:product_read_one.php?id=$id&msg=productUpdate_success");
                    }
                }
            } else {
                $target_file = $row['path_img'];
            }

            try {

                $query = "UPDATE products
                  SET name=:name, description=:description,
   price=:price, promotionprice=:promotionprice, path_img=:path_img, manufacturedate=:manufacturedate, expireddate=:expireddate WHERE p_id = :p_id";

                $stmt = $con->prepare($query);

                $name = htmlspecialchars(strip_tags($_POST['name']));
                $description = htmlspecialchars(strip_tags($_POST['description']));
                $price = htmlspecialchars(strip_tags($_POST['price']));
                $promotionprice = htmlspecialchars(strip_tags($_POST['promotionprice']));
                $path_img = $target_file;
                $manufacturedate = htmlspecialchars(strip_tags($_POST['manufacturedate']));
                $expireddate = htmlspecialchars(strip_tags($_POST['expireddate']));

                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotionprice', $promotionprice);
                $stmt->bindParam(':path_img', $path_img);
                $stmt->bindParam(':manufacturedate', $manufacturedate);
                $stmt->bindParam(':expireddate', $expireddate);
                $stmt->bindParam(':p_id', $id);

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
                        header("Location:product_read_one.php?id=$id&msg=productUpdate_success");
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post" enctype="multipart/form-data">
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
                    <td>Category</td>
                    <td>
                        <select class="form-control form-select fs-6 rounded" name="category_id">
                            <option value="">--Category--</option>
                            <?php
                            $query_category = "SELECT * FROM categorys";
                            $stmt_category = $con->prepare($query_category);
                            $stmt_category->execute();
                            if ($_POST){

                                while ($row3 = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row3);
                                    $selected_category = $row3['c_id'] == $_POST['category_id'] ? 'selected' : '';
                                    echo "<option class='bg-white' value='{$c_id}'$selected_category>$category_name</option>";
                                }
                            }else{
                                while ($row3 = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row3);
                                    $pre_category = $row3['c_id'] == $row['category_id'] ? 'selected' : '';
                                    echo "<option class='bg-white' value='{$c_id}'$pre_category>$category_name</option>";
                                }
                            }
                            
                            ?>
                    </td>
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
                    <td>
                        <p>Product Image</p>
                    </td>
                    <?php
                    if ($row['path_img'] == "uploads/noimg_product.png") {
                    ?>
                        <td><img src="<?php echo htmlspecialchars($path_img, ENT_QUOTES); ?>" style="object-fit: cover;height:100px;width:100px"><input type="file" name="fileToUpload" class="form-control" id="fileToUpload"></td>
                    <?php
                    } else {
                    ?>
                        <td><img src="<?php echo htmlspecialchars($path_img, ENT_QUOTES); ?>" id='image' style="object-fit: cover;height:100px;width:100px;">
                            <div class='row g-0'><input type="file" name="fileToUpload" class="form-control col me-2" id="fileToUpload"><input type='submit' class='btn btn-danger col-2' id="remove" name='remove_img' value='remove'></div>
                        </td>
                    <?php
                    }
                    ?>
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
                        <input type='submit' name='save' value='Save Changes' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to Product Listing</a>

                    </td>
                </tr>
            </table>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>