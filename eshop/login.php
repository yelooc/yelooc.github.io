<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS → -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <title>Sign In</title>
</head>
<style>
    body {
        margin: 100px;
        font-size: 12px;
    }
</style>

<body class="bg-light text-center">
    <!-- Your Content Starts Here -->

    <?php
    include 'config/database.php';

    session_start();

    if (isset($_POST['submit'])) {

        $sql = "SELECT * FROM customers";
        $stmt = $con->prepare($sql);
        $stmt->execute();

        $message = "";
        $message1 = "";
        $flag = 0;
        $flag_all = 0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if ($_POST['username'] == $row['username'] && $_POST['password'] == $row['password']) {
                if ($row['account_status'] == 'active') {
                    header("Location:home.php");
                } else {
                    $flag = 1;
                    $message = 'Your Account is suspended';
                }
            }else{
                    $flag_all_incorrect = 1;
                    $message_all_incorrect = 'Incorrect username or password';
                
            }
                
            if ($_POST['username'] == $row['username'] && $_POST['password'] != $row['password']) {
                $flag = 1;
                $message = 'Incorrect Password';
            }
            if ($_POST['username'] != $row['username'] && $_POST['password'] == $row['password']) {
                $flag = 1;
                $message = 'User not found (Invalid Account)';
            }
        }

        if (empty($_POST['password'])) {
            $flag = 1;
            $message = "Please insert your password.";
        }
        if (empty($_POST['username'])) {
            $flag = 1;
            $message = "Please insert your username.";
        }
        if ($flag == 1) {
            echo "<div class='alert alert-danger'>";
            echo $message;
            echo "</div>";
        }else{     
            if ($flag_all_incorrect == 1){ 
                echo "<div class='alert alert-danger'>";
                echo $message_all_incorrect;
                echo "</div>";
            }
        }
        
    }
    ?>




    <div class="d-flex justify-content-center">
        <form action="" class="col-3" method="POST">
            <img src="IMG\sign in ui logo.png" class="mb-3" width="60px" alt="logo">
            <p class="mb-3 fs-5">Please sign in</p>

            <div class="form-floating">
                <input type="text" class="form-control" name="username" id="floatingInput" placeholder="username">
                <label>Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password">
                <label>Password</label>
            </div>
            <div class="form-check-center mb-3 mt-2">
                <input class="form-check-input" type="checkbox" id="Checkbox">
                <label class="form-check-label">
                    Remember me
                </label>
            </div>
            <button type="submit" name="submit" class="btn btn-primary col col-12 mb-5">Sign in</button>
            <p class="text-secondary">©2017-2021</p>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>

</body>

</html>