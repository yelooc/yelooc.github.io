<?php
include 'session_login.php';
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
include 'config/database.php';
include 'nav.php';

try {

    $query = "SELECT * FROM categorys WHERE id = :id";
    $stmt = $con->prepare($query);

    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $category_name = $row['category_name'];
    $description = $row['description'];
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
    <title><?php echo htmlspecialchars($category_name, ENT_QUOTES);  ?> (Edit)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Update Category</h1>
        </div>

        <?php
        if ($_POST) {
            try {

                $query = "UPDATE categorys
                SET id=:id, category_name=:category_name, description=:description WHERE id = :id";

                $stmt = $con->prepare($query);

                $category_name = htmlspecialchars(strip_tags($_POST['category_name']));
                $description = htmlspecialchars(strip_tags($_POST['description']));

                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':category_name', $category_name);
                $stmt->bindParam(':description', $description);

                $flag = 0;
                $message = "";

                if (empty($description)){
                    $flag = 1;
                    $message = "Description cannot be empty";
                }
                if (empty($category_name)){
                    $flag = 1;
                    $message = "Category's Name cannot be empty";
                }

                if ($flag == 0) {
                    if ($stmt->execute()) {
                        header("Location:category_success_update_message.php?id=$id");
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Category's Name</td>
                    <td><input type='text' name='category_name' value="<?php echo htmlspecialchars($category_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='category_read.php' class='btn btn-danger'>Back to read category</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>