<?php

class CW_Util {
    
    public static function serverName($http = FALSE){
        $SN = $_SERVER['HTTP_HOST'];
        $R = str_replace('www.', '', $SN);
        if($http){$R = 'http://' . $R;}
        RETURN $R;
    }
    
    public static function documentRoot(){
        RETURN $_SERVER['DOCUMENT_ROOT'];
    }
    
    /**
     * Retorna o caminho root completi incluindo subdiretorios se existirem
     * @return type
     */
    public static function getCWD(){
        return str_replace('\\', '/', getcwd()) . '/';
    }


    public static function path(){
        RETURN str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', getcwd())) . '/';
    }

    public static function requestUri(){
        RETURN str_replace(CW_Util::path(), '', $_SERVER['REQUEST_URI']);
    }

}
