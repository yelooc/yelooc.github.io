<!--ID : 2030346 -->
<!--Name : NG WING Chun -->
<!--Topic : Exercise 3 generate the two random number which is bigger and smaller(if else short)-->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS → -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <title>Exercise 3 generate the two random number which is bigger and smaller(if else short)</title>
</head>
<body>

    <?php
    $n1 = rand(10,100);
    $n2 = rand(10,100);
    $bigger=$n2;
    $smaller=$n1;
    
    $bigger = ($n1>$n2) ? $bigger : $smaller;
    $smaller = ($n1>$n2) ? $smaller : $bigger;

    echo "<div class='fw-bold'>" . $bigger . "</div>";
    echo $smaller;
    
    ?>

    

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ"
        crossorigin="anonymous"></script>    
</body>

</html>