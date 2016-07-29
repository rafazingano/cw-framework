<?php

namespace framework\core;

class Request {
	private $SCRIPT_NAME 	= null;
	private $controller 	= null;
	private $action		= null;
	private $params         = null;
	
	public function __construct() {
		$this->SCRIPT_NAME 	= $_SERVER['SCRIPT_NAME'];
		$url_explode 		= explode('/', $this->SCRIPT_NAME);
		$barra_inicio 		= array_shift($url_explode);
		$this->controller 	= ($controller = array_shift($url_explode))? $controller : 'index';
		$this->action 		= ($action = array_shift($url_explode))? $action : 'index';
		$this->params 		= isset($url_explode[0])? $url_explode : null;
	}
	
    public static function get($key = null) {        
        if (isset($key) && ($key != '')) {
            return isset($_REQUEST[$key])? $_REQUEST[$key] : null;
        }  else {
            return isset($_REQUEST)? $_REQUEST : null;
        }        
    }

    public static function set($key, $val) {
        $_REQUEST[$key] = $val;
    }
	
	
}
