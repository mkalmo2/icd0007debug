<?php

$selectedGrade = $_GET['grade'] ?? 3;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Include example</title>
</head>
<body>

<header>
    <h3>Include content from other php file</h3>
</header>

<?php include 'form.php' ?>

<footer>
    <h6>Some footer text</h6>
</footer>

</body>
</html>
