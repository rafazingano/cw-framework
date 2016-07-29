<?php

namespace framework\core;

class Controller {

    protected $view     = null;

    public function __construct() {        
        $this->view     = new View();
    }
    
    public function theme($t = null){
        $this->view->theme($t);
    }

    public function model($model) {
        $this->$model   = new $model();
    }

    public function index() {
        die('Comando index do controle base');
    }

}
