<?php

$data = $_POST['data'] ?? '';

$message = "Data saved!";

if ($data) {
    file_put_contents('data.txt', $data);
    $message = sprintf("\nSaved %s bytes\n", strlen($data));
}

$url = 'index.php?message=' . $message;

header('Location: ' . $url);
