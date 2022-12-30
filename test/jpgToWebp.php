<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . '/../src/autoloader.php';
    $btd = new \sysborg\btd(__DIR__. '/1669982787114.jpg');
    //converts to webp and save at the current directory
    $btd->save(__DIR__. '/1669982787114.webp', 'webp');
?>