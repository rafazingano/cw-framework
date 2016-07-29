<?php

namespace framework\core;

class Route {

    private $routes = array();
    private $controller = null;
    private $action = null;
    private $structure = null;

    public function __construct() {
        $this->routes = require 'config/route.php';
        $this->structure = new Structure();
        $this->route();
    }

    function getController() {
        return $this->structure->getController('path') . '\\' . $this->controller . 'Controller';
    }

    function getAction() {
        return $this->action;
    }

    function setController($controller) {
        if (isset($controller)) {
            $this->controller = $controller;
        }
    }

    function setAction($action) {
        if (isset($action)) {
            $this->action = $action;
        }
    }

    private function controllerUrl() {
        $a = explode('?', Util::requestUri());
        $b = explode('/', $a[0]);
        $c = (isset($b[0]) and ! empty($b[0])) ? $b[0] : $this->structure->getController('class');
        return $c;
    }

    private function actionUrl() {
        $a = explode('?', Util::requestUri());
        $b = explode('/', $a[0]);
        $c = (isset($b[1]) and ! empty($b[1])) ? $b[1] : $this->structure->getController('method');
        return $c;
    }

    /**
     * Valida a url com as urls dispostas 
     * no arquivo routes de configuração
     * @param type $url
     * @return boolean
     */
    private function checkRoute($url) {
        $r_uri = explode('?', Util::requestUri());
        if ($r_uri[0] == $url) {
            return true;
        }
        if (!empty($r_uri[0]) and $url == '{:any}') {
            return true;
        }
        return false;
    }

    /**
     * Seta controler e actions(methods)
     * Não concontrando nada seta valores pre definidos
     * no arquivo de estrutura de configurações
     */
    private function route() {
        $this->controller = $this->controllerUrl();
        $this->action = $this->actionUrl();
        foreach ($this->routes as $k => $v) {
            if ($this->checkRoute($k)) {
                $this->controller = $v['controller'];
                $this->action = $v['action'];
                break;
            }
        }
    }

}
