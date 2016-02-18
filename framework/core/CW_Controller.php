<?php

class CW_Controller {

    protected $view = null;

    public function __construct() {        
        $this->view = new CW_View();
    }

    public function Model($model) {
        /*
        if (file_exists("models/{$model}.php")) {
            include_once "models/{$model}.php";
        } else {
            die("Modelo {$model} não encontrado na pasta modelos.");
        }
        */
        $this->$model = new $model();
    }

    public function index() {
        die('Comando index do controle base');
    }

}
