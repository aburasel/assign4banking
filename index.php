<?php
//require_once "app/autoload.php";

use App\Service\CustomerCLIApp;

require_once "vendor/autoload.php";
//Check Running from CLI
if (isset($argv[0])) {
  $customerApp = new CustomerCLIApp;
  $customerApp->run();
} else {
  session_start();
  if (isset($_SESSION['user_email'])) {
    header("Location:dashboard.php");
    exit;
  }else{
    header("Location:views/customer/login.php");
    exit;
  }

}