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