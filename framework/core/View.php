<?php

namespace framework\core;

class View {
    private $html           = null; /*html geral*/
    private $element        = null; /**/
    private $config         = null; /*arquivo de configuração*/
    private $extensions     = array('.php', '.html'); /*Extenções que ira buscar para montar views e themes*/
    private $view           = null; /*view selecionada*/
    private $theme          = null; /*Theme*/
    private $structure      = null;
    private $dom            = null;
    private $urlRefacAttr   = array('link'=>'href','a'=>'href','script'=>'src','img'=>'src');
    
    public function __construct() {
        $this->config       = new Config();
        $this->structure    = new Structure();
        $this->theme        = new Theme();
        $this->dom          = new Dom();
        $this->setView($this->structure->getView());
    }
    
    /**
     * Busca o html montado
     * @return type
     */
    function getHtml() {
        return $this->html;
    }
    
    /**
     * Seta um html para o projeto
     * @param type $html
     */
    function setHtml($html) {
        $this->html = $html;
    }
    
    /**
     * Busca as extenções que serao avaliadas em themes e views
     * @return type
     */
    function getExtensions() {
        return $this->extensions;
    }
    
    /**
     * Seta extenções para serem avaliadas em themes e views 
     */
    public function setExtensions($extensions) {
        $this->extensions = $extensions;
    }
    
    /**
     * retorna as informações da view
     * @param type $v
     * @return type
     */
    public function getView($v = null) {
        switch ($v) {
            case 'root':
                $r = $this->rootView();
                break;
            case 'root_file':
                $r = $this->rootFileView();
                break;
            default:
                $r = ($this->view[$v])? $this->view[$v] : $this->structure->getView($v);
                break;
        }
        return $r;
    }
    
    /**
     * Seta uma view
     * @param type $view
     * @param type $v
     */
    function setView($view, $v = null) {
        if(isset($view) AND !is_array($view)){
            $this->view[$v] = $view;
        }else{
            $this->view = is_array($view)? $view : null;
        }
    }
    
    /**
     * Seta os elementos a serem acrescentados no html    
     * @param type $k
     * @param type $v
     * @param type $attribute
     */
    public function setElement($k = null, $v = null, $attribute = 'innertext'){
        $this->element[$attribute][$k] = $v;
    }
    
    /**os elementos a serem acrescentados no html
     * retorna 
     * @param type $k
     * @param type $attribute
     * @return type
     */
    public function getElement($k = null, $attribute = 'innertext') {
        return (isset($this->element[$attribute][$k]) and $k !== null)? $this->element[$attribute][$k] : $this->element;
    }
    
    /**
     * Monta o html da view
     * @param type $v
     * @return type
     */
    private function html($v = null){
        if(isset($v) and !is_array($v)){ $this->setView($v, 'file'); }
        if(isset($v['path'])){  $this->setView($v['path'], 'path'); }
        if(isset($v['file'])){  $this->setView($v['file'], 'file'); }
        if(isset($v['block'])){ $this->setView($v['block'], 'block'); }
        $url_view = $this->getView('root_file');        
        if($url_view){
            return empty($this->getView('block'))? $this->dom->file_get_html($url_view) : $this->dom->file_get_html($url_view)->find($this->getView('block'), 0); 
        }else{
            return null;
        }
    }
    
    /**
     * Refatora as urls de todo o html
     * @param type $html
     * @param type $value
     */
    public function urlRefactoring($ura = null) {
        if($ura === FALSE){ $this->config->setUrlRefactoring(FALSE); }
        if($this->config->getUrlRefactoring() AND $this->html){
            foreach($this->urlRefacAttr as $k => $v){
                foreach ($this->html->find($k) as $element) {
                    if (!strstr($element->$v, 'http')) {
                        $element->$v = $this->theme->getTheme('server') . str_replace(array('../'), '', $element->$v);
                    }
                }
            }
        }
        return $this;
    }  
    
    /**
     * monta o html do theme
     * @param type $t
     * @return \framework\core\View
     */
    public function theme($t = null){
        $this->html = $this->theme->html($t); 
        return $this;
    }
    
    /**
     *  Monta o html da view 
     * @param type $v
     * @return \framework\core\View
     */
    public function view($v = null){
        if($this->html){
            $this->setElement($this->theme->getTheme('view'), $this->html($v));
        }else{
            $this->html = $this->html($v);
        }
        return $this;
    }
    
    /**
     * 
     * @return \framework\core\View
     */
    public function element(){
        $elements = $this->getElement();
        if($elements){
            foreach($elements as $attribute => $arrayElements){
                $this->addElement($this->html, $arrayElements, $attribute);
            }
        }
        return $this;
    }
    
    /**
     * Renderiza o html
     * @param type $_view
     */
    public function render($_view = null) {
        if(!isset($this->html)){
            $this->theme()->view($_view)->element()->urlRefactoring();
        }
        echo $this->html;
    }
    
     /**
     * verifica e retorna se existir o caminho root das views
     * @return type
     */
    private function rootView() {
        return Util::getCWD() . $this->getView('path') . '/';
    }
    
    /**
     * verifica e retorna se existir o caminho root da view
     * @return type
     */
    private function rootFileView() {
        $_root = $this->rootView() . $this->getView('file');
        foreach($this->extensions as $ext){
            if(file_exists($_root . $ext)){ $_root_exist = $_root . $ext; break; }  
        }
        return isset($_root_exist)? $_root_exist : NULL;
    }
    
    /**
     * Transforma os array e html para seren unificados com o metodo addHtmlElement
     * @param type $html ja buscada pelo metodo dom
     * @param type $elements array com local e valor a ser inserido no html
     * @param type $attribute tag ou posição dos elementos
     */
    private function addElement($html = null, $elements = null, $attribute = 'innertext'){
        foreach($elements as $key => $value){
            $this->addHtmlElement($html, $key, $value, $attribute);
        }
    }
    
    /**
     * Adiciona elementos ao html
     * @param type $html
     * @param type $key
     * @param type $value
     * @param type $attribute
     */
    private function addHtmlElement($html = null, $key = null, $value = null, $attribute = 'innertext'){
        foreach ($html->find($key) as $element){
            $element->$attribute = $value;            
        }             
    }

}
