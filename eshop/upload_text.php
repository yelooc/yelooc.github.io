<?php

// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $isUploadOK = 1;
    $isUploadOKs = true;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);


    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $isUploadOK = 1;
    } else {
        echo "File is not an image.";
        $isUploadOK = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $isUploadOKs = false;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $isUploadOKs = false;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($isUploadOKs == false) {
        echo "Sorry, your file was not uploaded."; // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

if (isset($_POST['delete'])){
    $file_pointer = $_FILES["fileToUpload"]["tmp_name"]; 
   
    // Use unlink() function to delete a file 
    if (!unlink($file_pointer)) { 
        echo ("$file_pointer cannot be deleted due to an error"); 
    } 
    else { 
        echo ("$file_pointer has been deleted"); 
    } 
}

?> 

<!DOCTYPE html>
<html>

<body>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
        <input type="submit" value="Delete Image" name="delete">
    </form>

</body>

</html>





<?php
            $array = array('');
            if ($num > 0) {
                echo "<table class='table table-hover table-responsive table-bordered m-0'"; //start table

                echo "<tr>";
                echo "<th class='col-6'>Product Name</th>";
                echo "<th>Quantity</th>";
                echo "<th></th>";
                echo "</tr>";
                echo "<table class='table table-hover table-responsive table-bordered' id='order_table'>"; //start table

                if (!$_POST) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        foreach ($array as $product_row => $product_ID) {
                            $product_list = '';
                            echo "<tr class='productRow'>";
                            echo "<td class='col-6'><select class='form-control' name='product[]'>";
                            echo "<option value=''>Product List</option>";
                            for ($pcount = 0; $pcount < count($product_arrName); $pcount++) {
                                $pre_selected = $product_arrID[$pcount] == $row['product_id'] ? 'selected' : '';
                                echo "<option value='" . $product_arrID[$pcount] . "'$pre_selected>" . $product_arrName[$pcount] . "</option>";
                            }
                            echo "</select>";
                            echo "</td>";
                            echo "<td><select class='form-select' name='quantity[]'>";
                            echo  '<option selected></option>';
                            for ($quantity = 1; $quantity <= 5; $quantity++) {
                                $quantity_selected =  $row['quantity'] == $quantity ? 'selected' : '';
                                echo "<option value='$quantity'$quantity_selected>$quantity</option>";
                            }
                            echo "</select></td>";
                            echo "<td><button type='button' class='btn mb-3 mx-2' onclick='deleteMe(this)'>X</button></td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    foreach ($array as $product_row => $product_ID) {
                        $product_list = $_POST['product'];
                        echo "<tr class='productRow'>";
                        echo "<td class='col-6'><select class='form-control' name='product[]'>";
                        echo "<option value=''>Product List</option>";
                        for ($pcount = 0; $pcount < count($product_arrName); $pcount++) {
                            $product_selected = $product_arrID[$pcount] == $product_list[$product_row] ? 'selected' : '';
                            echo "<option value='" . $product_arrID[$pcount] . "'$product_selected>" . $product_arrName[$pcount] . "</option>";
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "<td><select class='form-select' name='quantity[]'>";
                        echo  '<option selected></option>';
                        for ($quantity = 1; $quantity <= 5; $quantity++) {
                            $quantity_selected = $quantity == $_POST['quantity'][$product_row] ? 'selected' : '';
                            echo "<option value='$quantity'$quantity_selected>$quantity</option>";
                        }
                        echo "</select></td>";
                        echo "<button type='button' class='btn btn-danger' onclick='deleteMe(this)'>X</button>";
                        echo "</tr>";
                    }
                }
            }
            echo "</table>";
            echo "<div class='d-flex justify-content-between'>";
            echo "<div>";
            echo "<button type='button' class='add_one btn btn-primary me-2'>Add More Product</button>";
            echo "<button type='button' class='delete_one btn btn-danger'>Delete Last Product</button>";
            echo "</div>";
            echo "<div>";
            echo "<input type='submit' value='Save Changes' class='btn btn-primary me-2'/>";
            echo "<a href='neworder_read.php' class='btn btn-danger'>Back to Order Listing</a>";

            echo "</div>";
            echo "</table>";
            ?>