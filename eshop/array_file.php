<?php

if ($_POST) {
    for ($x = 0; $x < count($_POST['product']); $x++) {
        if ($_POST['product'][$x] === '' && $_POST['quantity'][$x] === '') {
            unset($_POST['product'][$x]);
            unset($_POST['quantity'][$x]);
        }
    }
    var_dump($_POST['product']);
    var_dump($_POST['quantity']);
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <form method="POST">
        <?php
        $post_product = $_POST ? count($_POST['product']) : 1 ;
        // for ($x=0;$x<$post_product;$x++){
        foreach ($_POST['product'] as $key => $value) {
        ?>
            set
            <div class="productRow">
                <select name="product[]">
                    <option value></option>
                    <option value="a">a</option>
                    <option value="b">b</option>
                    <option value="c">c</option>
                </select>
                <select name="quantity[]">
                    <option value></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
        <?php
        // }
        }
        ?>
        <button type="button" class="add_one btn btn-primary">Add More Product</button>
        <button type="button" class="delete_one btn btn-danger">Delete Last Product</button>
        <input type='submit' value='Save' />
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.productRow');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.delete_one')) {
                var total = document.querySelectorAll('.productRow').length;
                if (total > 1) {
                    var element = document.querySelector('.productRow');
                    element.remove(element);
                }
            }
        }, false);
    </script>
</body>

</html>