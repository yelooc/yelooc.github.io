<?php
include 'session_login.php';
include 'config/database.php';
include 'nav.php';
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Create Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<body>
<div class="container">

<div class="page-header">
    <h1>Create Category</h1>
</div>
<?php 
if ($_POST){
    try {

        $query = "INSERT INTO categorys SET category_name=:category_name,
                description=:description";

$stmt = $con->prepare($query);
$category_name = $_POST['category_name'];
$description = $_POST['description'];

$stmt->bindParam(':category_name', $category_name);
                $stmt->bindParam(':description', $description);

                $flag = 0;
                $message = "";

                if (empty($description)){
                    $flag=1;
                    $message = "Description cannot be empty";
                }
                if (empty($category_name)){
                    $flag=1;
                    $message = "Category's Name cannot be empty";
                }else{
                    if ($category_name == $row['category_name']){
                        $flag = 1;
                        $message = "Category's Name is already exit";
                    }
                }

                if ($flag == 0 ) {
                    if ($stmt->execute()) {
                        $last_id = $con->lastInsertid();
                        header("Location:category_read_one.php?id=$last_id&&msg=categoryCreate_success");
                    }
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo $message;
                    echo "</div>";
                }

    }catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
}

?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Category's Name</td>
                    <td><input type='text' name='category_name' class='form-control' value="<?php if ($_POST) {
                                                                                            echo $_POST['category_name'];
                                                                                        } ?>" /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name="description" rows="5" class="form-control"><?php if ($_POST) {
                                                                                        echo $_POST['description'];
                                                                                    } ?></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='category_read.php' class='btn btn-danger'>Back to Category Listing</a>

                    </td>
                </tr>
            </table>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>
