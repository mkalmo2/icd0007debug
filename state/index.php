<?php

include 'functions.php';

$list1 = [1, 2, 3, 4];
$list2 = [5, 6];

if (isset($_GET['list1_to_list2'])) {
    $value = $_GET['list1'];
    $list1 = removeElementByValue($value, $list1);
    $list2[] = $value;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rakenduse seisu hoidmine</title>
</head>
<body>

<form>

    <input type="hidden" name="list1_state" value="">
    <input type="hidden" name="list2_state" value="">

    <select name="list1" multiple="multiple">
        <?php foreach ($list1 as $number): ?>
            <option value="<?= $number ?>"><?= $number ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" name="list1_to_list2">&gt;&gt;</button>
    <button type="submit" name="list2_to_list1">&lt;&lt;</button>

    <select name="list2" multiple="multiple">
        <?php foreach ($list2 as $number): ?>
            <option value="<?= $number ?>"><?= $number ?></option>
        <?php endforeach; ?>
    </select>

</form>

</body>
</html>
