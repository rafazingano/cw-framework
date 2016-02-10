<?php

class CW_Config{
    private $urlRefactoring  = true; 
    private $debug           = false;
    
    public function __construct() {
    
    }
    function getUrlRefactoring() {
        return $this->urlRefactoring;
    }

    function getDebug() {
        return $this->debug;
    }

    function setUrlRefactoring($urlRefactoring) {
        $this->urlRefactoring = $urlRefactoring;
    }

    function setDebug($debug) {
        $this->debug = $debug;
    }
    
}