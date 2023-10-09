<?php
//require_once "app/autoload.php";

use App\Service\AdminApp;

require_once "vendor/autoload.php";

if (isset($argv[0])) {
    $adminApp = new AdminApp();
    $adminApp->run();
} else {
    session_start();
    if (isset($_SESSION['admin_email'])) {
        header("Location:views/admin/customers.php");
        exit;
    } else {
        header("Location:views/admin/login.php");
        exit;
    }
}
