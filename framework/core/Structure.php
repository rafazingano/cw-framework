<?php

class CW_Structure {
    private $view           = null;
    private $theme          = null;
    private $controller     = null;
    private $configStructure = null;
    
    function __construct() {
        $this->configStructure = require 'config/structure.php';
        $this->setController($this->configStructure['controller']);
        $this->setTheme($this->configStructure['theme']);
        $this->setView($this->configStructure['view']);
    }
 
    function getView($p = null) {
        return isset($p)? $this->view[$p] : $this->view;
    }

    function getTheme($p = null) {
        return isset($p)? $this->theme[$p] : $this->theme;
    }

    function getController($p = null) {
        return isset($p)? $this->controller[$p] : $this->controller;
    }

    function setView($view, $p = null) {
        if(isset($p) AND !is_array($p)){
            $this->view[$p] = $view;
        }else{
            $this->view = $view;
        }
    }

    function setTheme($theme, $p = null) {
        if(isset($p) AND !is_array($p)){
            $this->theme[$p] = $view;
        }else{
            $this->theme = $theme;
        }
    }

    function setController($controller, $p = null) {
        if(isset($p) AND !is_array($p)){
            $this->controller[$p] = $view;
        }else{
            $this->controller = $controller;
        }
    }


}
