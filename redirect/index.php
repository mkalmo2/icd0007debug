<?php

$message = $_GET['message'] ?? '';

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>

<?php if ($message): ?>

    <h1><?=$message ?></h1>

<?php else: ?>

    <form action="saver.php" method="post">
        <input name="data" />
    </form>

<?php endif; ?>


</body>
</html>
