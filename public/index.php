<?php

session_start();

require_once '../vendor/autoload.php';

require_once '../config/config.php';

require_once '../config/database.php';

use App\Helpers\Router;

Router::route();