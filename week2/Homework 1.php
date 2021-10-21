<!--ID : 2030346 -->
<!--Name : NG WING Chun -->
<!--Topic : Homework 1 About use PHP to generate date select menu(for loop) -->
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS → -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <title>Homework 1 About use PHP to generate date select menu(for loop)</title>
</head>

<body>
    <div class="text-center m-5">
        <span class="fw-bold">What is your date of birth?</span><br>

        <select class="bg-primary fs-3 rounded" id="day" name="day">
            <option class='bg-white' selected>Day</option>
            <?php

            for ($day = 1; $day <= 31; $day++) {

                echo "<option class='bg-white' value='$day'>$day</option>";
            }
            ?>
        </select>

        <select class="bg-warning fs-3 rounded" id="month" name="month">
            <option class='bg-white' selected>Month</option>
            <?php

            for ($month = 1; $month <= 12; $month++) {

                echo "<option class='bg-white'>" . $month . "</option>";
            }

            ?>
        </select>

        <select class="bg-danger fs-3 rounded" id="year" name="year">
            <option class='bg-white' selected>Year</option>
            <?php

            for ($year = 1990; $year <= 2021; $year++) {

                echo "<option class='bg-white'>" . $year . "</option>";
            }

            ?>
        </select>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
</body>

</html>