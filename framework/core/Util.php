<?php

class CW_Util {
    public static function serverName($http = FALSE){
        $SN = $_SERVER['HTTP_HOST'] . CW_Util::localRoot();
        $R = str_replace('www.', '', $SN);
        if($http){$R = 'http://' . $R;}
        RETURN $R;
    }
    
    public static function requestUri(){
        RETURN str_replace(CW_Util::localRoot(), '', $_SERVER['REQUEST_URI']);
    }

    public static function localRoot(){
        RETURN str_replace(array('index.php', $_SERVER['DOCUMENT_ROOT']), '', $_SERVER['SCRIPT_FILENAME']);
    }
    
    public static function documentRoot(){
        RETURN $_SERVER['DOCUMENT_ROOT'] . CW_Util::localRoot();
    }
    
}
