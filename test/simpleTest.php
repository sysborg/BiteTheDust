<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . '/../src/BTDException.class.php';
    require_once __DIR__ . '/../src/btd.class.php';

    $a = new \sysborg\btd(__FILE__);
?>