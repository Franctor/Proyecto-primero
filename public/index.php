<?php
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';
use controllers\Router;

$router = new Router();
$router->route();
