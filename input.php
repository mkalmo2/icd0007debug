<?php

$name = $_POST['name'] ?? '';

print $name;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>

    <form>
        <input id="name"/>

        <br /><br />

        <button>Save</button>

        <button>Delete</button>
    </form>

</body>
</html>
