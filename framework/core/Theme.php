<?php

namespace framework\core;

class Theme {    
    private $theme      = null;
    private $extensions = array('.php', '.html');
    
    function __construct() {
        $this->structure    = new Structure();
    }
    
    /**
     * Verifica e retorna o servername do theme
     * @return type
     */
    private function serverTheme() {
        return Util::serverName(true) . Util::path() . $this->getTheme('path') . '/' . $this->getTheme('theme') . '/';
    }
    
    /**
     * Verifica e retorna o caminho root do theme
     * @return type
     */
    private function rootTheme() {
        return Util::getCWD() . $this->getTheme('path') . '/' . $this->getTheme('theme') . '/';
    }
    
    /**
     * Verifica e retorna se existir o arquivo do tema
     * @return type
     */
    private function rootFileTheme() {
        $_root = $this->rootTheme() . $this->getTheme('file');
        foreach($this->extensions as $ext){
            if(file_exists($_root . $ext)){ $_root_exist = $_root . $ext; break; }  
        }
        return isset($_root_exist)? $_root_exist : NULL;
    }
     
    /**
     * Busca os valores de estrutura do theme
     * @param type $p
     * @return type
     */
    function getTheme($p = null) {
        switch ($p) {
            case 'server':
                $r = $this->serverTheme();
                break;
            case 'root':
                $r = $this->rootTheme();
                break;
            case 'root_file':
                $r = $this->rootFileTheme();
                break;
            default:
                $r = (isset($p) and isset($this->theme[$p]))? $this->theme[$p] : $this->structure->getTheme($p);
                break;
        }
        return $r;
    }
    
    /**
     * Seta valores de estrutura para os themes
     * @param type $theme
     * @param type $t
     */
    function setTheme($theme = null, $t = null) {
        if(isset($theme) and isset($t) and !is_array($theme)){
            $this->theme[$theme] = $t;
        }else{
            $this->theme = $theme;
        }
    }
    
    /**
     * Busca extenções dos themes
     * @return type
     */
    function getExtensions() {
        return $this->extensions;
    }

    /**
     * Setar extenções para verificar os themes
     * @return type
     */
    function setExtensions($extensions) {
        $this->extensions = $extensions;
    }
}
