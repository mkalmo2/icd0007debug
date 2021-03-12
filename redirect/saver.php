<?php

$data = $_POST['data'] ?? '';

$url = 'index.php?message=' . "Data saved!";

header('Location: ' . $url);
