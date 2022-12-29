<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . '/../src/autoloader.php';

    $a = new \sysborg\btd(__DIR__. '/1669982787114.jpg');

    /** proportional scale test height */
    $a->proportional(height: 100)
      ->save(__DIR__.'/testeHeight.webp', 'webp');

    /** proportional scale test width */
    $a->proportional(100)
    ->save(__DIR__.'/testeWidth.webp', 'webp');
?>