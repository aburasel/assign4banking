<?php

use App\Database\Migration;
use App\Storage\DB;
require_once "vendor/autoload.php";

$db = new DB();
$migration = new  Migration($db);
$migration->run();
