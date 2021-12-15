<?php
// get passed parameter value, in this case, the record USERNAME
// isset() is a PHP function used to verify if a value is there or not
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

include 'config/database.php';

// read current record's data
try {
    // prepare select query
    $query = "SELECT username, email, firstname, lastname, gender, date_of_birth, account_status, password FROM customers WHERE username = :username";
    $stmt = $con->prepare($query);

    // Bind the parameter
    $stmt->bindParam(":username", $id);

    // execute our query
    $stmt->execute();

    // store retrieved row to a variable
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // values to fill up our form
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
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>

        <?php
// check if form was submitted
if ($_POST) {
    try {
        // write update query
        // in this case, it seemed like we have so many fields to pass and
        // it is better to label them and not use question marks
        $query = "UPDATE customers
                  SET username=:username, firstname=:firstname,
                  lastname=:lastname, email=:email, gender=:gender, date_of_birth=:date_of_birth, password=:new_password, account_status=:account_status WHERE username = :username";
        // prepare query for excecution
        $stmt = $con->prepare($query);
        // posted values
        $username = htmlspecialchars(strip_tags($_POST['username']));
        $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
        $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
        $email = htmlspecialchars(strip_tags($_POST['email']));
        $gender = htmlspecialchars(strip_tags($_POST['gender']));
        $date_of_birth = htmlspecialchars(strip_tags($_POST['date_of_birth']));
        $new_password = htmlspecialchars(strip_tags($_POST['new_password']));
        $account_status = htmlspecialchars(strip_tags($_POST['account_status']));
        // bind the parameters
        $stmt->bindParam(':username', $id);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':new_password', $new_password);
        $stmt->bindParam(':account_status', $account_status);
        // Execute the query
        $flag = 0;
        $message = "";

        if (empty($_POST['old_pasword']) && empty($_POST['new_password']) && empty($_POST['comfirm_password'])) {
            $flag = 0;
            $unwork_change_new_password = htmlspecialchars(strip_tags($row['password']));
            $stmt->bindParam(':new_password', $unwork_change_new_password);
        }

        if (!preg_match("/[a-zA-Z0-9]{1,}/", $username)) {
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
        }
        if (!preg_match("/[a-zA-Z0-9]{1,}/", $lastname)) {
            $message = "Last Name cannot be empty";
            $flag = 1;
        }
        if (!preg_match("/[a-zA-Z-0-9]{1,}/", $firstname)) {
            $message = "First Name cannot be empty";
            $flag = 1;
        } else {
            if (preg_match("/\s/", $firstname)) {
                $message = "First Name cannot contain space";
                $flag = 1;
            }
        }
        if (!preg_match("/[a-z0-9]{1,}/", $email)) {
            $message = "Email cannot be empty";
            $flag = 1;
        } else {
            if (!preg_match("/@/", $email) || !preg_match("/\./", $email)) {
                $message = "Email must include @ and .";
                $flag = 1;
            }
            if (substr($email, -5, -4) == '@') {
                $message = "You must fill in xxxxx@gmail.com or others example hotmail, yahoo";
                $flag = 1;
            }
            if (preg_match("/\s/", $email)) {
                $message = "Email cannot contain space";
                $flag = 1;
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
                    <td>Username</td>
                    <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' /></td>
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
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div> <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>