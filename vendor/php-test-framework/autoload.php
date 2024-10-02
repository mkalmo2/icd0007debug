<?php

spl_autoload_register(function ($className) {
    $parts = explode('\\', $className);

    if ($parts[0] === 'tplLib') {
        $basePath = __DIR__ . '/parser';
        array_shift($parts);
    } else if ($parts[0] === 'Facebook') {
        $basePath = __DIR__ . '/php-webdriver/webdriver/lib';
        array_shift($parts);
        array_shift($parts);
    } else if (str_starts_with($parts[0], 'Simple')) {
        $basePath = __DIR__ . '/simpletest';
    } else {
        $basePath = __DIR__;
        array_shift($parts);
    }

    $filePath = sprintf('%s/%s.php', $basePath, implode('/', $parts));

    require_once $filePath;
});
