<?php

error_log(print_r($_POST, true));

$data = $_POST['data'] ?? '';

error_log('save: ' . $data);

$url = 'index.php?message=' . "Data\n saved!";

error_log('url: ' . $url);

header('Location: ' . $url);
