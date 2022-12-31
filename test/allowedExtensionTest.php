<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    use sysborg\btd;

    require_once __DIR__ . '/../src/autoloader.php';
    //must return true
    var_dump(btd::isExtensionAllowed('jpg'));

    //must return false
    var_dump(btd::isExtensionAllowed('docx'));
?>