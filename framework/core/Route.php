<?php

namespace framework\core;

class Route {
    private $routes     = array();
    private $controller = "index";
    private $action     = "index";
    private $structure  = null;
    
    public function __construct() { 
        $this->routes = require 'config/route.php';        
        $this->structure  = new Structure();
        $this->route();
    }
    
    function getController() {
        return $this->structure->getController('path') . '\\' . $this->controller . 'Controller';
    }

    function getAction() {
        return $this->action;
    }

    function setController($controller) {
        if(isset($controller)){
            $this->controller = $controller;
        }
    }

    function setAction($action) {
        if(isset($action)){
            $this->action = $action;
        }
    }

    function route(){  
        if(array_key_exists(Util::requestUri(), $this->routes)){
            $this->controller = $this->routes[Util::requestUri()]['controller'];
            $this->action = $this->routes[Util::requestUri()]['action'];
        }else{
            $url_exp = explode('?', Util::requestUri());
            $url_expl = explode('/', $url_exp[0]);
            if(isset($url_expl[0]) and !empty($url_expl[0])){ $this->controller = $url_expl[0]; }
            if(isset($url_expl[1]) and !empty($url_expl[1])){ $this->action = $url_expl[1]; }
        }
    } 
}