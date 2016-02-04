<?php

class Config extends CW_Config{
   
    protected $inner = array(
        'title' => 'My CW Framework',
        'a[class="navbar-brand"]' => 'CW Framework',
        'attributes'    => array(
            'meta[name="description"]' => array(
                'content' => 'My CW Framework'
            ),
            'meta[name="author"]' => array(
                'content' => 'My CW Framework'
            )
        ),
        'block' => array(
            array(
                'parent' => '[class="navbar-right"]',
                'child' => 'li[class="active"]',
                'content' => array(
                    array(
                        'a' => array(
                            'content' => 'My CW Framework', 
                            'attributes' => array(
                                'class' => 'My CW Framework',
                                'title' => 'My CW Framework'
                            )
                        ),
                        'b' => 'My CW Framework' 
                    )
                ),
                'attributes' => array(
                    'a' => array(
                        'class' => 'My CW Framework',
                        'title' => 'My CW Framework'
                    )
                )
            )
        )
    );
    

}