<?php

class CW_Util {
    public static function serverName($http = FALSE){
		//$SN = $_SERVER['SERVER_NAME'];
		$SN = $_SERVER['HTTP_HOST'];
		$R = str_replace('www.', '', $SN);
		if($http){$R = 'http://' . $R;}
		//if($_SERVER['SERVER_PORT']){ $R .= ':' . $_SERVER['SERVER_PORT']; }
		RETURN $R;
		

    }
    
    public static function documentRoot(){
		$R = $_SERVER['DOCUMENT_ROOT'];
		RETURN $R;
    }
    
}
