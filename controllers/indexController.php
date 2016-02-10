<?php

class IndexController extends CW_Controller {

    public function index() {

        
//        $this->view->setInnerText('title', 'My CW Framework Funciona');
//        $this->view->setInnerText('a[class="navbar-brand"]', 'CW Framework');  

//        $posts = array(
//            'block' => array(
//                array(
//                    'parent' => 'ul[class="navbar-right"]',
//                    'child' => 'li[class="active"]',
//                    'content' => array(
//                        array(
//                            'a' => array(
//                                'content' => 'Rafael normal00009', 
//                                'attributes' => array(
//                                    'class' => 'sdaaaaaaaaaaaaaf',
//                                    'title' => 'aaaaaaaaaaaaaa'
//                                )
//                            ),
//                            'b' => 'Rafael active' 
//                        )
//                    )
//                ),
//                array(
//                    'parent' => 'ul[class="navbar-right"]',
//                    'child' => 'li',
//                    'content' => array(
//                        array(                            
//                            'a' => 'Rafael active',
//                            'b' => 'Rafael Zingano'
//                        )
//                    ),
//                    'attributes' => array(
//                        'a' => array(
//                            'class' => 'sdf',
//                            'title' => 'teste title'
//                        )
//                    )
//                )
//
//            ),
//            'teste' => 'linkssss'
//        );

//        $this->view->setInnerText('posts', $posts);
//        
//        $this->view->setInnerText('attributes', array('li' => array('class' => 'ssssssssss', 'title' => 'teste title')));
        
        $this->view->view('index/index');
       
    }
    
    public function test() {
        
        $this->view->setInnerText('title', 'My CW Framework');
        
        $this->view->view(array('view' => 'index/test', 'block_view' => 'div[class="teste"]'));
       
    }

}
