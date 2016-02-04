<?php

class AdminController extends CW_Controller {

    public function index() {
   
        $this->view->view('index/index');
       
    }
    
    public function test() {
        
        $this->view->setInnerText('title', 'My CW Framework');
        
        $this->view->view(array('view' => 'index/test', 'block_view' => 'div[class="teste"]'));
       
    }

}
