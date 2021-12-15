<!--ID : 2030346 -->
<!--Name : NG WING Chun -->
<!--Topic : Eshop Customer Create to insert the data in database(PDO Method)-->
<?php
include 'session_login.php';
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Eshop Customer Create to insert the data in database(PDO Method)</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
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
                            <a class="nav-link dropdown-toggle active text-white" href="#" role="button" data-bs-toggle="dropdown">
                                Customer
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item bg-secondary" href="#">Create Customer</a></li>
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
            <h1>Create Customer</h1>
        </div>

        <!-- html form to create product will be here -->
        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO customers SET username=:username, 
                email=:email, password=:password,
                firstname=:firstname, 
                lastname=:lastname,
                gender=:gender, 
                date_of_birth=:date_of_birth
                ";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                // $crytp_password = md5($password);
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];  
                $date_of_birth = $_POST['date_of_birth'];
                // bind the parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':gender', $_POST['gender']);
                $stmt->bindParam(':date_of_birth', $date_of_birth);
                // Execute the query  
                $flag = 0;
                $message = "";

                //empty validation
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
                if (!preg_match("/[a-zA-Z0-9]{1,}/", $_POST['comfirm_password'])) {
                    $message = "Comfirm Password cannot be empty";
                    $flag = 1;
                } else {
                    //comfirm password validation
                    if ($_POST['comfirm_password'] != $password) {
                        $message = "Comfirm Password should fill same with password";
                        $flag = 1;
                    }
                }
                if (!preg_match("/[a-zA-Z0-9]{1,}/", $password)) {
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

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='email' name='email' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Comfirm Password</td>
                    <td><input type='password' name='comfirm_password' class='form-control' /></td>
                </tr>
                <tr>
                    <td>First name</td>
                    <td><input type='text' name='firstname' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last name</td>
                    <td><input type='text' name='lastname' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="row ms-5">
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" value="male">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Male
                                </label>
                            </div>
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2" value="female">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Female
                                </label>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>Date of birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>