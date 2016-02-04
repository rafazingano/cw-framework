<?php

/*Busca o config default do sistema*/
require_once 'framework/core/Config.php';
/*Busca o config do sistema*/
require_once 'config/config.php';
require_once 'framework/core/Request.php';
require_once 'framework/core/Controller.php';
require_once 'framework/core/Model.php';
require_once 'framework/core/View.php';
require_once 'framework/core/DB.php';
require_once 'framework/core/Util.php';
require_once 'framework/core/Routes.php';
require_once 'config/routes.php';
/* Library */
require_once 'framework/library/simple_html_dom.php';

$routes = new Routes();
$routes->setController(CW_Request::get('controller'));
$routes->setAction(CW_Request::get('action'));
$routes->route();
$controller = $routes->getController();
$action     = $routes->getAction();

if (file_exists("controllers/" . $controller . ".php")) {
    require_once "controllers/" . $controller . ".php";
    $Controller = new $controller();
    if (method_exists($Controller, $action)) {
        $Controller->$action();
    } else {
        die('Page not found!');
    }
} else {
    die("O Controller <strong>" . $controller . "</strong> não existe na pasta Controller do MVC");
}