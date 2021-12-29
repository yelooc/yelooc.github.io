<!--ID : 2030346 -->
<!--Name : NG WING Chun -->
<!--Topic : Eshop Customer Create to insert the data in database(PDO Method)-->
<?php
include 'session_login.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
include 'config/database.php';
include 'nav.php';

?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Eshop Customer Create to insert the data in database(PDO Method)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container">

        <div class="page-header">
            <h1>Create Customer</h1>
        </div>

        <?php
        $query_customer = 'SELECT * FROM customers';
        $stmt_customer = $con->prepare($query_customer);
        $stmt_customer->execute();
        $row = $stmt_customer->fetch(PDO::FETCH_ASSOC);
        $num = $stmt_customer->rowCount();

        if ($_POST) {

            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $isUploadOK = 1;
            $isUploadOKs = true;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

            include 'config/database.php';
            try {
                $query_customer = "SELECT * FROM customers";
                $stmt_customer = $con->prepare($query_customer);
                $stmt_customer->execute();
                $row = $stmt_customer->fetch(PDO::FETCH_ASSOC);
                $num = $stmt_customer->rowCount();

                $query = "INSERT INTO customers SET username=:username,
                path=:path, 
                email=:email, password=:password,
                firstname=:firstname, 
                lastname=:lastname,
                gender=:gender, 
                date_of_birth=:date_of_birth
                ";

                $stmt = $con->prepare($query);
                $username = $_POST['username'];
                $path = $target_file;
                $email = $_POST['email'];
                $password = $_POST['password'];
                // $crytp_password = md5($password);
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $date_of_birth = $_POST['date_of_birth'];

                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':path', $path);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':gender', $_POST['gender']);
                $stmt->bindParam(':date_of_birth', $date_of_birth);

                $flag = 0;
                $message = "";

                if ($num > 0) {

                    $row = $stmt_customer->fetch(PDO::FETCH_ASSOC);

                    if (!preg_match("/[a-zA-Z0-9]{1,}/", $date_of_birth)) {
                        $message = "Date Of Bith cannot be empty";
                        $flag = 1;
                    } else {
                        //date of birth validation
                        if ($date_of_birth . substr(0, 4) >= 2005) {
                            $message = "You must be 18 years old or above";
                            $flag = 1;
                        }
                    }
                    if (!preg_match("/[a-zA-Z0-9]{1,}/", $_POST['gender'])) {
                        $message = "Please select your gender";
                        $flag = 1;
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
                    if (empty($_POST['comfirm_password'])) {
                        $message = "Comfirm Password cannot be empty";
                        $flag = 1;
                    } else {
                        //comfirm password validation
                        if ($_POST['comfirm_password'] != $password) {
                            $message = "Comfirm Password should fill same with password";
                            $flag = 1;
                        }
                    }
                    if (empty($password)) {
                        $message = "Password cannot be empty";
                        $flag = 1;
                    } else {
                        //password validation
                        if (!preg_match("/[A-Z]/", $password)) {
                            $message = "password need Upper letter";
                            $flag = 1;
                        }
                        if (!preg_match("/[0-9]/", $password)) {
                            $message = "password need Numerial";
                            $flag = 1;
                        }
                        if (!preg_match("/[0-9A-Za-z]{8,}/", $password)) {
                            $message = "password need more than 8 charater";
                            $flag = 1;
                        }
                        if (preg_match("/\s/", $password)) {
                            $message = "Password cannot contain space";
                            $flag = 1;
                        }
                        if ($password == $row['password']) {
                            $message = "Password is already exit";
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

                        if ($email == $row['email']) {
                            $message = "Email is already exit";
                            $flag = 1;
                        }
                    }
                    if (empty($username)) {
                        $message = "Username cannot be empty";
                        $flag = 1;
                    } else {
                        //username validation
                        if (!preg_match("/[a-zA-Z0-9]{6,}/", $username)) {
                            $message = "Username should more than 6 charater";
                            $flag = 1;
                        }
                        if (preg_match("/\s/", $username)) {
                            $message = "Username cannot contain space";
                            $flag = 1;
                        }

                        if ($username == $row['username']) {
                            $message = "Username is already exit";
                            $flag = 1;
                        }
                    }
                }

                if ($check !== false) {
                    $isUploadOK = 1;
                } else {
                    $message = "File is not an image.";
                    $isUploadOK = 0;
                }
                if ($num > 0) {

                    while ($row = $stmt_customer->fetch(PDO::FETCH_ASSOC)) {
                        if ($row['path'] == $target_file) {
                            $message = "Sorry, your Image's name is exit already.";
                            $isUploadOKs = false;
                        }
                    }
                }
                
                // if (empty($target_file)) {
                //     $target_file = "uploads/noimg_product.png";
                //     $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                //     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                //     $isUploadOKs = true;
                // }

                // Check file size
                if ($_FILES["fileToUpload"]["size"] > 5000000) {
                    $message = " Sorry, your file is too large.";
                    $isUploadOKs = false;
                }
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $isUploadOKs = false;
                }

                if ($flag == 0 && $isUploadOKs == true && $isUploadOK == 1) {

                    if ($stmt->execute() && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        header("Location:customer_success_create_message.php?id={$id}");
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

        <form action="<?php echo $_SERVER["PHP_SELF"] . "?id={$id}" ?>" method="POST" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' value="<?php if ($_POST) {
                                                                                            echo $_POST['username'];
                                                                                        } ?>" /></td>
                </tr>
                <tr>
                    <td>
                        <p>Customer Image</p>
                    </td>
                    <td><input type="file" name="fileToUpload" class="form-control" id="fileToUpload"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' class='form-control' value="<?php if ($_POST) {
                                                                                        echo $_POST['email'];
                                                                                    } ?>" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' value="<?php if ($_POST) {
                                                                                                echo $_POST['password'];
                                                                                            } ?>" /></td>
                </tr>
                <tr>
                    <td>Comfirm Password</td>
                    <td><input type='password' name='comfirm_password' class='form-control' value="<?php if ($_POST) {
                                                                                                        echo $_POST['comfirm_password'];
                                                                                                    } ?>" /></td>
                </tr>
                <tr>
                    <td>First name</td>
                    <td><input type='text' name='firstname' class='form-control' value="<?php if ($_POST) {
                                                                                            echo $_POST['firstname'];
                                                                                        } ?>" /></td>
                </tr>
                <tr>
                    <td>Last name</td>
                    <td><input type='text' name='lastname' class='form-control' value="<?php if ($_POST) {
                                                                                            echo $_POST['lastname'];
                                                                                        } ?>" /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="row ms-5">
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" value="male" <?php if ($_POST) {
                                                                                                                                    if ($_POST['gender'] == "male") { ?> checked <?php }
                                                                                                                                                                            } ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Male
                                </label>
                            </div>
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2" value="female" <?php if ($_POST) {
                                                                                                                                        if ($_POST['gender'] == "female") { ?> checked <?php }
                                                                                                                                                                                } ?>>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Female
                                </label>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>Date of birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' value="<?php if ($_POST) {
                                                                                                echo $_POST['date_of_birth'];
                                                                                            } ?>" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <?php
                        if (filter_var($_SESSION['correct_username'], FILTER_VALIDATE_EMAIL)) {
                            $query = 'SELECT * from customers WHERE email= ?';
                        } else {
                            $query = 'SELECT * FROM customers WHERE username=?';
                        }

                        $stmt = $con->prepare($query);
                        $stmt->bindParam(1, $_SESSION['correct_username']);
                        $stmt->execute();
                        $numCustomer = $stmt->rowCount();

                        if ($numCustomer > 0) {
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            extract($row);
                            echo "<a href='customer_read.php?id={$username}' class='btn btn-danger'>Back to read customers</a>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>