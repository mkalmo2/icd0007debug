<?php

require_once 'functions.php';

$display = $_POST['display'] ?? '';
$cmd = $_POST['cmd'] ?? '';
$number = $_POST['number'] ?? '';

if ($cmd === 'insert') {
    $display .= $number;
} else if ($cmd === 'plus') {
    $display .= '+';
} else if ($cmd === 'minus') {
    $display .= '-';
} else if ($cmd === 'evaluate') {
    $display = evaluate($display);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>

<form method="post">
    Display: <input type="text"
                    readonly="readonly"
                    name="display" value="<?= $display ?>" />

    <br /><br />

    Number: <input type="text" name="number" />
    <button type="submit"
            name="cmd"
            id="insert"
            value="insert">Insert</button>
    <br />
    <button type="submit" name="cmd" value="plus">+</button>
    <button type="submit" name="cmd" value="minus">-</button>

    <br /><br />

    <button type="submit" name="cmd" value="evaluate">Evaluate</button>

</form>

</body>
</html>
