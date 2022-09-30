<?php

$data = $_POST['data'] ?? '';

// some code that should save the data.

$url = 'index.php?message=' . "Data saved!";

header('Location: ' . $url);
