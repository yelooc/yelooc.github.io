<?php
include 'session_login.php';
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
include 'config/database.php';
include 'nav.php';
try {

    $query = "SELECT path, username, email, firstname, lastname, gender, date_of_birth, registration_date_and_time,  account_status FROM customers WHERE username = :username";
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
    $registration_date_and_time = $row['registration_date_and_time'];
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
    <title><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></h1>
        </div>
        <br>
        <img src="<?php echo htmlspecialchars($path, ENT_QUOTES); ?>" style='object-fit: cover;height:100px;width:100px;'>
        <br><br>
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Username</td>
                <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($firstname, ENT_QUOTES) . " ";
                    echo htmlspecialchars($lastname, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo htmlspecialchars($email, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Date Of Birth</td>
                <td><?php echo htmlspecialchars($date_of_birth, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Registration Date And Time</td>
                <td><?php echo htmlspecialchars($registration_date_and_time, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Account Status</td>
                <td><?php echo htmlspecialchars($account_status, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php
                    echo "<a href='customer_update.php?id=$id' class='btn btn-primary me-2'>Edit</a>";
                    ?>
                    <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>
                </td>
            </tr>
        </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>