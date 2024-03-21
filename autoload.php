<?php

declare(strict_types=1);

require_once(__DIR__ . '/vendor/autoload.php');

const INPUT_RESOURCE = __DIR__ . '/resource/input.txt';
const DIRECTORY = __DIR__ . '\\app';

set_include_path(__DIR__);

spl_autoload_register(function ($className) {
    $filePath = DIRECTORY . '/' . $className . '.php';
    $filePath = str_replace('\\', '/', $filePath);

    if (file_exists($filePath)) {
        require_once $filePath;

        return;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(DIRECTORY));
    foreach ($iterator as $file) {
        if ($file->isDir()) {
            continue;
        }

        $subFilePath = $file->getPathname();
        if (basename($subFilePath, '.php') === basename($className)) {
            require_once $subFilePath;
            return;
        }
    }
});
