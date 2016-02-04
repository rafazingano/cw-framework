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
/* Library */
require_once 'framework/library/simple_html_dom.php';

$controller       = CW_Request::get('controller');
$view             = CW_Request::get('view');

if ($controller == null){ $controller = "index"; }
$controller .= 'Controller';

if (file_exists("controllers/{$controller}.php")) {
    require_once "controllers/{$controller}.php";
} else {
    die("O Controller <strong>{$controller}</strong> não existe na pasta Controller do MVC");
}

$Controller = new $controller();

if ($view == null) {
    $view = 'index';
}

if (method_exists($Controller, $view)) {
    $Controller->$view();
} else {
    die('Page not found!');
}
