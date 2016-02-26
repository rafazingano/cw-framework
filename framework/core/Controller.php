<?php

namespace framework\core;

class Controller {

    protected $view = null;

    public function __construct() {        
        $this->view = new View();
    }

    public function model($model) {
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
