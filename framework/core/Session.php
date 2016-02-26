<?php

namespace framework\core;

class Session{
    private $session = null;
    
    public static function get($s = null) {
        if($s){
            //return $this->session[$s];
            return $_SESSION[$s];
        }else{
            //return $this->session;
            return $_SESSION;
        }
    }

    public static function set($session, $s) {        
        if($s){
            //$this->session[$session] = $s;
            $_SESSION[$session] = $s;
        }else{
            //$this->session = $session;
            $_SESSION = $session;
        }
    }

}

