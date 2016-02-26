<?php

use framework\core\Route;

spl_autoload_register(function ($class) {
    require_once(str_replace('\\', '/', $class . '.php'));
});

$routes = new Route();
$controller = $routes->getController();
$action     = $routes->getAction();
$Controller = new $controller();
if (method_exists($Controller, $action)) {
    $Controller->$action();
} else {
    die('Page not found!');
}