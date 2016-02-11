<?php

function __autoload($class_name){
    $config_structure = require 'config/structure.php';
    foreach($config_structure as $directory){
        $arq = $directory['path'] . '/' . $class_name . '.php';
        if(file_exists($directory['path'] . '/' . $class_name . '.php')){
            require_once($directory['path'] . '/' . $class_name . '.php');
            return;
        }           
    }
}

$routes = new CW_Route();
//$routes->setController(CW_Request::get('controller'));
//$routes->setAction(CW_Request::get('action'));
$routes->route();
$controller = $routes->getController();
$action     = $routes->getAction();
$Controller = new $controller();
if (method_exists($Controller, $action)) {
    $Controller->$action();
} else {
    die('Page not found!');
}