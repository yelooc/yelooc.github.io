<!--ID : 2030346 -->
<!--Name : NG WING Chun -->
<!--Topic : Eshop Customer Create to insert the data in database(PDO Method)-->
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

<div class="collapse navbar-collapse">
    <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link text-secondary" href="home.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-secondary" href="product_create.php">Create Product</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active text-white" href="#">Create Customer</a>
        </li>
        <li class="nav-item ">
            <a class="nav-link text-secondary" href="contact_us.php">Contact us</a>
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
                $query = "INSERT INTO customers SET username=:username, password=:password, firstname=:firstname, 
                lastname=:lastname,
                gender=:gender, 
                date_of_birth=:date_of_birth, 
                registration_date_and_time=:registration_date_and_time,
                account_status=:account_status,
                created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $username = $_POST['username'];
                $password = $_POST['password'];
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $gender = $_POST['gender'];
                $date_of_birth = $_POST['date_of_birth'];
                $registration_date_and_time = $_POST['registration_date_and_time'];
                $account_status = $_POST['account_status'];
                // bind the parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':date_of_birth', $date_of_birth);
                $stmt->bindParam(':registration_date_and_time', $registration_date_and_time);
                $stmt->bindParam(':account_status', $account_status);
                $created = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':created', $created);
                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
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
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' /></td>
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
                    <td>Registration Date & Time</td>
                    <td><input type='date' name='registration_date_and_time' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Account status</td>
                    <td>
                        <div class="row ms-5">
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="account_status" id="flexRadioDefault3" value="active">
                                <label class="form-check-label" for="flexRadioDefault3">
                                    Active
                                </label>
                            </div>
                            <div class="form-check col">
                                <input class="form-check-input" type="radio" name="account_status" id="flexRadioDefault4" value="inactive">
                                <label class="form-check-label" for="flexRadioDefault4">
                                    Inactive
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <!-- <a href='index.php' class='btn btn-danger'>Back to read products</a> -->
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>