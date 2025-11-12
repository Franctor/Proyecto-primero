<?php
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';
use controllers\Router;
use helpers\Session;

Session::start();
$router = new Router();
$router->route();
