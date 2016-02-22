<?php

class CW_Theme {    
    private $theme = null;
    
    function __construct() {
        $this->structure    = new CW_Structure();
    }
    
    function getTheme($p = null) {
        if(isset($p) and isset($this->theme[$p])){
            return $this->theme[$p];
        }else{
            return $this->structure->getTheme($p);
        }
    }
    
    function setTheme($theme, $t = null) {
        if(isset($t) AND !is_array($t)){
            $this->theme[$theme] = $t;
        }else{
            $this->theme = $theme;
        }
    }
}
