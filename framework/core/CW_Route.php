<?php

class CW_Route {
    public $routes   = array();
    private $controller = "index";
    private $action     = "index";
    
    public function __construct() { 
        $this->routes = require 'config/route.php';
    }
    
    function getController() {
        return $this->controller . 'Controller';
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
        if(array_key_exists(CW_Util::requestUri(), $this->routes)){
            $this->controller = $this->routes[CW_Util::requestUri()]['controller'];
            $this->action = $this->routes[CW_Util::requestUri()]['action'];
        }else{
            $url_exp = explode('?', CW_Util::requestUri());
            $url_expl = explode('/', $url_exp[0]);
            if(isset($url_expl[0]) and !empty($url_expl[0])){ $this->controller = $url_expl[0]; }
            if(isset($url_expl[1]) and !empty($url_expl[1])){ $this->action = $url_expl[1]; }
        }
    } 
}