<?php

$count = $_GET['count'] ?? 3;

if ($count > 0) {
    header('Location: redirect.php?count=' . ($count - 1));
    exit;
}

var_dump($count);

