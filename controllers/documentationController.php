<?php

class DocumentationController extends CW_Controller {

    public function index() {
   
        $this->view->view('documentation/index');
       
    }
    
    
    public function controller() {
   
        $this->view->view('documentation/controller');
       
    }

}
