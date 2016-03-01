<?php

namespace controllers;
use framework\core\Controller;

class IndexController extends Controller {

    public function index() {

        $this->view->render('index/index');
       
    }

}
