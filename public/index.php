<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

use App\Core\Router;

$config = require '../config/app.php';

define('BASE_PATH', $config['base_path']);

require_once '../app/Core/Router.php';

$router = new Router();
$router->run();

