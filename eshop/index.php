<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <title>Sign In</title>
</head>
<style>
    body {
        margin: 100px;
    }
</style>

<body class="bg-light text-center">

    <?php
    session_start();

    include 'config/database.php';

    $message = "";
    $flag = 0;

    if (isset($_POST['submit'])) {

        if (empty($_POST['password'])) {
            $flag = 1;
            $message = "Please insert your password.";
        }
        if (empty($_POST['username'])) {
            $flag = 1;
            $message = "Please insert your username or email.";
        }

        if ($flag == 0) {
            $username = $_POST['username'];
            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $query = 'SELECT username, email, password, account_status from customers WHERE email= ?';
            } else {
                $query = 'SELECT username, email, password, account_status from customers WHERE username= ?';
            }
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $num = $stmt->rowCount();

            if ($num > 0) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);

                if ($_POST['password'] == $row['password']) {
                    if ($row['account_status'] == 'active') {
                        $_SESSION["correct_username"] = $_POST['username'];

                        header("Location:home.php");
                    } else {
                        $flag = 1;
                        $message = 'Your Account is suspended';
                    }
                } else {
                    $flag = 1;
                    $message = 'Incorrect username or password';
                }
            } else {
                $flag = 1;
                $message = 'User or Email not found (Invalid Account)';
            }
        }
    }
    ?>

    <div class="d-flex justify-content-center">
        <form action="" class="col-3" method="POST">
            <img src="img\sign_in_ui_logo.png" class="mb-3" width="60px" alt="logo">
            <p class="mb-3 fs-6">Please sign in</p>
            <?php
            $message1 = '';
            if (!empty(isset($_GET['msg']) && $_GET['msg'])) {
                if ($_POST) {
                } else {
                    if (isset($_GET['msg']) && $_GET['msg'] == 'signup_success') {
                        echo "<div class='alert alert-success'>Sign Up Succesfully</div>";
                    }
                    if (isset($_GET['msg']) && $_GET['msg'] == 'logout') {
                        echo "<div class='alert alert-success'>Log Out Succesfully</div>";
                    }
                    if (isset($_GET['msg']) && $_GET['msg'] == 'pleaselogin') {
                        echo "<div class='alert alert-danger'>Please login first, then can access to next page.</div>";
                    }
                }
            }

            if (isset($_POST['submit'])) {
                if (isset($flag) && $flag == 1) {
                    echo "<div class='alert alert-danger'>";
                    echo $message;
                    echo "</div>";
                }
            }
            ?>

            <div class="form-floating">
                <input type="text" class="form-control" name="username" id="floatingInput" placeholder="username">
                <label>Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password">
                <label>Password</label>
            </div>
            <br><br>
            <button type="submit" name="submit" class="btn btn-primary col-12">Sign in</button>
            No Account? <a href="sign_up.php">Sign Up</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>

</body>

</html>