<?php
include 'session_login.php';
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
include 'config/database.php';
include 'nav.php';

try {

    $query = "SELECT path, username, email, firstname, lastname, gender, date_of_birth, account_status, password FROM customers WHERE username = :username";
    $stmt = $con->prepare($query);

    $stmt->bindParam(":username", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $path = $row['path'];
    $username = $row['username'];
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $email = $row['email'];
    $gender = $row['gender'];
    $date_of_birth = $row['date_of_birth'];
    $password = $row['password'];
    $account_status = $row['account_status'];
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
    <title><?php echo htmlspecialchars($username, ENT_QUOTES);  ?> (Edit)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        
        $("#remove").click(function() {
            $("#image").attr("src", "uploads/noimg_customer.png");
        });

        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return true;
            }
        });

    });
</script>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>

        <?php
        if (isset($_POST['remove_img'])) {
            echo "<div class='alert alert-danger'>The Image has been deleted</div>";
            if ($row['path'] != 'uploads/noimg_customer.png') {
                if (file_exists($row['path'])) {
                    unlink($row['path']);
                }
            } else {
            }

            $target_file = "uploads/noimg_customer.png";
            $query = "UPDATE customers SET path=:path where username=:username";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':username', $id);
            $stmt->bindParam(':path', $target_file);
            $stmt->execute();
        }

        if (isset($_POST['save'])) {

            if (!empty($_FILES['fileToUpload']['name'])) {

                if ($row['path'] != 'uploads/noimg_customer.png') {
                    unlink($row['path']);
                }
                $target_dir = "uploads/" . $row['username'];
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
                        header("Location:customer_read_one.php?id=$id&msg=customerUpdate_success");
                    }
                }
            } else {
                $target_file = $row['path'];
            }

            try {
                $query = "UPDATE customers
                  SET path=:path, username=:username, firstname=:firstname,
                  lastname=:lastname, email=:email, gender=:gender, date_of_birth=:date_of_birth, password=:new_password, account_status=:account_status WHERE username = :username";

                $stmt = $con->prepare($query);

                $path = $target_file;
                $username = htmlspecialchars(strip_tags($_POST['username']));
                $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                $email = htmlspecialchars(strip_tags($_POST['email']));
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $date_of_birth = htmlspecialchars(strip_tags($_POST['date_of_birth']));
                $new_password = htmlspecialchars(strip_tags($_POST['new_password']));
                $account_status = htmlspecialchars(strip_tags($_POST['account_status']));

                $stmt->bindParam(':username', $id);
                $stmt->bindParam(':path', $path);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':date_of_birth', $date_of_birth);
                $stmt->bindParam(':new_password', $new_password);
                $stmt->bindParam(':account_status', $account_status);

                $flag = 0;
                $message = "";

                $query_username = "SELECT username FROM customers where username=:username";
                $stmt_username = $con->prepare($query_username);
                $stmt_username->bindParam(":username", $username);
                $stmt_username->execute();
                $num_username = $stmt_username->rowCount();

                $query_password = "SELECT password FROM customers where password=:password";
                $stmt_password = $con->prepare($query_password);
                $stmt_password->bindParam(":password", $password);
                $stmt_password->execute();
                $num_password = $stmt_password->rowCount();

                $query_email = "SELECT email FROM customers where email=:email";
                $stmt_email = $con->prepare($query_email);
                $stmt_email->bindParam(":email", $email);
                $stmt_email->execute();
                $num_email = $stmt_email->rowCount();

                if (empty($_POST['old_pasword']) && empty($_POST['new_password']) && empty($_POST['comfirm_password'])) {
                    $flag = 0;
                    $unwork_change_new_password = htmlspecialchars(strip_tags($row['password']));
                    $stmt->bindParam(':new_password', $unwork_change_new_password);
                }

                if (empty($lastname)) {
                    $message = "Last Name cannot be empty";
                    $flag = 1;
                }
                if (empty($firstname)) {
                    $message = "First Name cannot be empty";
                    $flag = 1;
                } else {
                    if (preg_match("/\s/", $firstname)) {
                        $message = "First Name cannot contain space";
                        $flag = 1;
                    }
                }
                if (empty($email)) {
                    $message = "Email cannot be empty";
                    $flag = 1;
                } else {
                    if (substr($email, -4) != '.com') {
                        $message = "Not a Valid Email";
                        $flag = 1;
                    }
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    } else {
                        $message = "Not a Valid Email (E.g: xxx@example.com)";
                        $flag = 1;
                    }
                    if (preg_match("/\s/", $email)) {
                        $message = "Email cannot contain space";
                        $flag = 1;
                    }
                    if ($num_email > 0) {
                        if ($row['email'] != $email) {
                            $message = "Email is already exit";
                            $flag = 1;
                        }
                    }
                }

                if (!empty($_POST['old_password'])) {
                    if ($_POST['old_password'] != $row['password']) {
                        $flag = 1;
                        $message = "Incorrect old password";
                    } else {
                        if (empty($_POST['new_password'])) {
                            $flag = 1;
                            $message = "New Password cannot be empty";
                        } else {
                            //comfirm password validation
                            if (empty($_POST['comfirm_password'])) {
                                $flag = 1;
                                $message = "comfirm password cannot be empty";
                            } else {
                                if ($_POST['comfirm_password'] != $_POST['new_password']) {
                                    $flag = 1;
                                    $message = "Comfirm Password should be same with New Password";
                                }
                            }

                            //new password validation
                            if (!preg_match("/[A-Z]/", $_POST['new_password'])) {
                                $message = "New Password need Upper letter";
                                $flag = 1;
                            }
                            if (!preg_match("/[0-9]/", $_POST['new_password'])) {
                                $message = "New Password need Numerial";
                                $flag = 1;
                            }
                            if (!preg_match("/[0-9A-Za-z]{8,}/", $_POST['new_password'])) {
                                $message = "New Password need more than 8 charater";
                                $flag = 1;
                            }
                            if (preg_match("/\s/", $_POST['new_password'])) {
                                $message = "New Password cannot contain space";
                                $flag = 1;
                            }
                            if ($_POST['new_password'] == $_POST['old_password']) {
                                $flag = 1;
                                $message = "new password cannot same with old password";
                            }
                            if ($num_password > 0) {
                                if ($row['password'] != $_POST['new_password']) {
                                    $message = "Password is already exit";
                                    $flag = 1;
                                }
                            }
                        }
                    }
                }

                if ($flag == 0) {
                    if ($stmt->execute()) {
                        header("Location:customer_read_one.php?id=$id&msg=customerUpdate_success");
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
                    <td>
                        <p>Customer Image</p>
                    </td>
                    <?php
                    if ($row['path'] == "uploads/noimg_customer.png") {
                    ?>
                        <td><img src="<?php echo htmlspecialchars($path, ENT_QUOTES); ?>" style="object-fit: cover;height:100px;width:100px"><input type="file" name="fileToUpload" class="form-control" id="fileToUpload"></td>
                    <?php
                    } else {
                    ?>
                        <td><img src="<?php echo htmlspecialchars($path, ENT_QUOTES); ?>" id='image' style="object-fit: cover;height:100px;width:100px;">
                            <div class='row g-0'><input type="file" name="fileToUpload" class="form-control col me-2" id="fileToUpload"><input type='submit' class='btn btn-danger col-2' id="remove" name='remove_img' value='remove'></div>
                        </td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><input type="text" class='form-control' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" READONLY></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='firstname' value="<?php echo htmlspecialchars($firstname, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='lastname' value="<?php echo htmlspecialchars($lastname, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' value="<?php echo htmlspecialchars($email, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="row ms-5">
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" value="male" <?php if ($row['gender'] == "male")  echo "checked"  ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Male
                                </label>
                            </div>
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2" value="female" <?php if ($row['gender'] == "female")  echo "checked"  ?>>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Female
                                </label>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>Date of birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' value="<?php echo htmlspecialchars($date_of_birth, ENT_QUOTES);  ?>" /></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <div class="row ms-5">
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="account_status" id="flexRadioDefault3" value="active" <?php if ($row['account_status'] == "active") { ?> checked <?php } ?>>
                                <label class="form-check-label" for="flexRadioDefault3">
                                    Active
                                </label>
                            </div>
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="account_status" id="flexRadioDefault4" value="inactive" <?php if ($row['account_status'] == "inactive") { ?> checked <?php } ?>>
                                <label class="form-check-label" for="flexRadioDefault4">
                                    Inactive
                                </label>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>Old Password</td>
                    <td><input type='password' name='old_password' class='form-control' /></td>
                </tr>
                <tr>
                    <td>New Password</td>
                    <td><input type='password' name='new_password' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Comfirm Password</td>
                    <td><input type='password' name='comfirm_password' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' name='save' value='Save Changes' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to Customer Listing</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>