<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . '/../src/autoloader.php';
    $btd = new \sysborg\btd(__DIR__. '/largeExample.webp');
    $btd->crop(100, 120, 300, 250)->save(__DIR__. 'cropped.jpg', 'jpg');
?>