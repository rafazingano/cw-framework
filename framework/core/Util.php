<?php

class CW_Util {
    
    public static function localRoot(){
        RETURN substr(substr(str_replace(array('index.php', $_SERVER['DOCUMENT_ROOT']), '', $_SERVER['SCRIPT_FILENAME']), 1), 0, -1);
    }
    
    public static function serverName($http = FALSE){
        $SN = $_SERVER['HTTP_HOST'];
        $R = str_replace('www.', '', $SN);
        if($http){$R = 'http://' . $R;}
        RETURN $R;
    }
    
    public static function documentRoot(){
        RETURN $_SERVER['DOCUMENT_ROOT'];
    }
    
    public static function requestUri(){
        RETURN str_replace(CW_Util::localRoot(), '', $_SERVER['REQUEST_URI']);
    }

    
    
    
}
