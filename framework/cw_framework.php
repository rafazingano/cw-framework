<?php

function __autoload($class_name){
    $directorys = array(
        'framework/core/',
        'framework/library/',
        'controllers/',
        'models/'
    );
    foreach($directorys as $directory){
        if(file_exists($directory.$class_name . '.php')){
            require_once($directory.$class_name . '.php');
            return;
        }           
    }
}

$routes = new CW_Route();
$routes->setController(CW_Request::get('controller'));
$routes->setAction(CW_Request::get('action'));
$routes->route();
$controller = $routes->getController();
$action     = $routes->getAction();

if (file_exists("controllers/" . $controller . ".php")) {
    //require_once "controllers/" . $controller . ".php";
    $Controller = new $controller();
    if (method_exists($Controller, $action)) {
        $Controller->$action();
    } else {
        die('Page not found!');
    }
} else {
    die("O Controller <strong>" . $controller . "</strong> não existe na pasta Controller do MVC");
}