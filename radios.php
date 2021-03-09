<?php

$actualGrade = $_GET['grade'] ?? 3;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
<form>

<?php foreach (range(1, 5) as $grade): ?>

    <input type="radio"
           name="grade"
           <?= $grade === $actualGrade ? 'checked' : ''; ?>
           value="<?= $grade ?>" />
    <?= $grade ?>

<?php endforeach; ?>

</form>
</body>
</html>
