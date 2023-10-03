<?php

spl_autoload_register(function ($className) {
    $parts = explode('\\', $className);

    if ($parts[0] === 'tplLib') {
        $basePath = __DIR__ . '/parser';
    } else if ($parts[0] === 'Facebook') {
        $basePath = __DIR__ . '/php-webdriver/webdriver/lib';
        array_shift($parts);
    } else {
        $basePath = __DIR__;
    }

    array_shift($parts);

    $filePath = sprintf('%s/%s.php', $basePath, implode('/', $parts));

    require_once $filePath;
});
