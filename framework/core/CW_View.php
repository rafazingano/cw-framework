<?php

class CW_View {
    private $html           = null; /*html geral*/
    private $innerText      = null; /*vai armazenar todos os valores que serao utilizados no html*/
    private $config         = null; /*arquivo de configuração*/
    private $extensions     = array('.php', '.html'); /*Extenções que ira buscar para montar views e themes*/
    private $view           = null; /*view selecionada*/
    public $theme          = null; /*Theme*/
    private $structure      = null;
    private $dom            = null;
    
    public function __construct() {
        $this->config       = new CW_Config();
        $this->structure    = new CW_Structure();
        $this->theme        = new CW_Theme();
        $this->dom          = new CW_Dom();
    }
    
    function getHtml() {
        return $this->html;
    }
    
    function setHtml($html) {
        $this->html = $html;
    }

    function getExtensions() {
        return $this->extensions;
    }
    
    function setExtensions($extensions) {
        $this->extensions = $extensions;
    }
    
    /**
     * verifica e retorna se existir o caminho root das views
     * @return type
     */
    private function rootView() {
        return CW_Util::getCWD() . $this->getView('path') . '/';
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
     * retorna as informações da view
     * @param type $v
     * @return type
     */
    function getView($v = null) {
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
    
    function setView($view, $v = null) {
        if(isset($v) AND !is_array($v)){
            $this->view[$v] = $view;
        }else{
            $this->view = is_array($view)? $view : null;
        }
    }
    
    public function setInnerText($k, $v) {
        $this->innerText[$k] = $v;
    }
    public function getInnerText($k = null) {
        if ($k === null) {
            return $this->innerText ;
        } else {
            if (isset($this->innerText[$k]) && ($this->innerText[$k] != null ) && ($this->innerText[$k] != '' )) {
                return $this->innerText[$k];
            } else {
                return null;
            }
        }
    }
    
    private function attributes($html = null, $value = null){
        foreach($value as $k => $v){
            foreach($v as $attr_k => $attr_v){
                $this->addAttributes($html, $k, $attr_v, $attr_k);
            }
        }
    }
    
    private function addAttributes($html = null, $key = null, $value = null, $attribute = 'innertext'){        
        if (method_exists($html,"find")){   
            foreach ($html->find($key) as $element){
                $element->$attribute = $value;            
            }
        }
    }
    
    private function innerText(){
        if($this->html and $this->getInnerText()){
            foreach($this->getInnerText() as $key => $value){
                $this->addInnerText($this->html, $key, $value);
            }
        }
    }
    
    private function addInnerTextBlock($html = null, $val = null){
        foreach($val as $content_v){
            $attributes = isset($content_v['attributes'])? $content_v['attributes'] : null;                       
            foreach ($html->find($content_v['parent'] . ' ' . $content_v['child']) as $e){
                $block = str_get_html($e->outertext);
                if($attributes){ $this->attributes($block, $attributes); }
                foreach($content_v['content'] as $child_k => $child_v){
                    foreach($child_v as $c_k => $c_v){ 
                        foreach ($block->find($c_k) as $child_element){
                                $child_element->innertext = !is_array($c_v)? $c_v : $c_v['content']; 
                                if(isset($c_v['attributes'])){ $this->attributes($block, array($c_k => $c_v['attributes'])); }
                            $v[$content_v['parent']] = isset($v[$content_v['parent']])? $v[$content_v['parent']] : null . $block;                                       
                        } 
                    } 
                }
            }
        }
        return isset($v)? $v : null;
    }
    
    private function addInnerText($html = null, $key = null, $value = null, $attribute = 'innertext'){
        if(!is_array($value)){
            $this->addAttributes($html, $key, $value, $attribute);
        }else if($key === 'attributes'){
            $this->attributes($html, $value);               
        }else{
            foreach($value as $k => $v){
                $k = empty($k)? $key : $k; 
                if($k === 'block'){                  
                    $v = $this->addInnerTextBlock($html, $v);
                    $k = rand();
                }
                $this->addInnerText($html, $k, $v, $attribute);
            }
        }
    }
    
    public function urlRefactoring($_finds = null) {
        $finds = isset($_finds)? $_finds : array('link'=>'href','a'=>'href','script'=>'src','img'=>'src');
        if($this->config->getUrlRefactoring() AND $this->html){
            foreach($finds as $k => $v){
                foreach ($this->html->find($k) as $element) {
                    if (!strstr($element->$v, 'http')) {
                        $element->$v = $this->theme->getTheme('server') . str_replace(array('../'), '', $element->$v);
                    }
                }
            }
        }
    }    

    /**
     * Retorna o html dom da view para ser trabalhado
     * @return type
     */
    public function viewHtml(){
        $url_view = $this->getView('root_file');
        if($url_view){
            return empty($this->getView('block'))? $this->dom->file_get_html($url_view) : $this->dom->file_get_html($url_view)->find($this->getView('block'), 0); 
        }else{
            return null;
        }
    }
    
    /**
     * retorna o html dom do theme
     * @return type
     */
    public function themeHtml(){
        $url_theme = $this->theme->getTheme('root_file');
        if($url_theme){
            return empty($this->theme->getTheme('block'))? $this->dom->file_get_html($url_theme) : $this->dom->file_get_html($url_theme)->find($this->theme->getTheme('block'), 0); 
        }else{
            return null;
        }
    }
    
    /**
     * Mount the HTML uniting theme and view it there
     */
    private function html(){
        $htmlView = $this->viewHtml();
        $this->html = $this->themeHtml();        
        if(isset($this->html) AND isset($htmlView) AND $this->theme->getTheme('view')){
            foreach ($this->html->find($this->theme->getTheme('view')) as $element){
                $element->innertext = $htmlView;            
            }
        }
        if(!isset($this->html) and isset($htmlView)){
            $this->html = $htmlView;
        }
    }
            
    public function view($_view = null) {
        //if($_view){
        //    $_view = is_array($_view)? $_view : array('view' => array('file' => $_view));
        //    $this->setView($_view['view']);
        //    if(isset($_view['theme'])){ $this->theme->setTheme($_view['theme']); }
        //}
        $this->html();
        $this->innerText();
        $this->urlRefactoring();
        echo $this->html;
    }

}
