<?php

namespace controllers;
use framework\core\Controller;

class DocumentationController extends Controller {

    public function index() {
   
        $this->view->view('documentation/index');
       
    }
    
    
    public function controller() {
   
        $this->view->view('documentation/controller');
       
    }

}
