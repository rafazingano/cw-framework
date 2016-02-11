<?php

class CW_View {
    private $html           = null; /*html geral*/
    private $innerText      = null; /*vai armazenar todos os valores que serao utilizados no html*/
    private $config         = null; /*arquivo de configuração*/
    private $extensions     = array('.php', '.html'); /*Extenções que ira buscar para montar views e themes*/
    private $view           = null; /*view selecionada*/
    private $blockView      = null; /*bloco a ser utilizado da view*/
    private $rootView       = null; /*local root das views*/
    private $defaultTheme   = null; /*Theme selecionado*/
    private $blockTheme     = null; /*bloco a ser utilizado do theme*/
    private $viewTheme      = null; /*bloco no theme onde sera montado a view*/
    private $indexTheme     = null; /*arquivo index do theme*/
    private $rootTheme      = null; /*Caminho root dos themes*/
    private $structure      = null;
    
    public function __construct() {
        $this->config   = new CW_Config();
        $this->structure = new CW_Structure();
    }
    
    function file_get_html($url, $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = 'UTF-8', $stripRN=true, $defaultBRText="\r\n", $defaultSpanText=" ") {
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
        $contents = file_get_contents($url, $use_include_path, $context, $offset);
        if (empty($contents) || strlen($contents) > MAX_FILE_SIZE){
            return false;
        }
        $dom->load($contents, $lowercase, $stripRN);
        return $dom;
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

    function getView() {
        return ($this->view)? $this->view : $this->structure->getView('index');
    }
    function setView($view) {
        $this->view = $view;
    }

    function getBlockView() {
        return isset($this->blockView)? $this->blockView : $this->structure->getView('block');
    }
	function setBlockView($blockView) {
        $this->blockView = $blockView;
    }
	
    function getRootView() {
        $v = isset($this->rootView)? $this->rootView : $this->structure->getView('path');
        return CW_Util::documentRoot() . CW_Util::path() . $v;
    }
    function setRootView($rootView) {
        $getLocalRoot = $this->config->getLocalRoot();
        $this->rootView = CW_util::documentRoot() . CW_Util::path() . $rootView;
    }

    function getTheme() {
        return $this->getDefaultTheme();
    }
	function setTheme($theme) {
        $this->setDefaultTheme($theme);
    }
	
	function getDefaultTheme() {
        return isset($this->defaultTheme)? $this->defaultTheme : $this->structure->getTheme('default');
    }
	function setDefaultTheme($theme) {
        $this->defaultTheme = $theme;
    }

    function getBlockTheme() {
        return $this->blockTheme;
    }
	function setBlockTheme($blockTheme) {
        $this->blockTheme = $blockTheme;
    }

    function getViewTheme() {
        return isset($this->viewTheme)? $this->viewTheme : $this->structure->getTheme('view');
    }
	function setViewTheme($viewTheme) {
        $this->viewTheme = $viewTheme;
    }

    function getIndexTheme() {
        return isset($this->indexTheme)? $this->indexTheme : $this->structure->getTheme('index');
    }
    function setIndexTheme($indexTheme) {
        $this->indexTheme = $indexTheme;
    }
	
    function getRootTheme() {
        $t = isset($this->rootTheme)? $this->rootTheme : $this->structure->getTheme('path');
        return CW_Util::documentRoot() . CW_Util::path() . $t;
    }
    function setRootTheme($rootTheme) {
        $this->rootTheme = $rootTheme;
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
    
    private function innerText($html = null){
        if($html and $this->getInnerText()){
            foreach($this->getInnerText() as $key => $value){
                $this->addInnerText($html, $key, $value);
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
                        $element->$v = CW_Util::serverName(true) . CW_Util::path() . $this->structure->getTheme('path') . '/' . $this->structure->getTheme('default') . '/' . $element->$v;
                    }
                }
            }
        }
        //return $this;
    }
    
    private function urlViewRoot($_view = null, $view_root_exist = null){
        $v = isset($_view) ? $_view : $this->getView();
        $view_root = $this->getRootView() . '/' . $v;
        foreach($this->extensions as $ext){
            if(file_exists($view_root . $ext)){
                $view_root_exist = $view_root . $ext;
            }
        }
        return $view_root_exist;
    }    
    
	/*
	Monta a url do theme.
	Procura pelo arquivo existente
	*/
    private function urlThemeRoot($_theme = null, $_index_theme = null, $theme_root_exist = null){
        $t              = isset($_theme) ? $_theme : $this->getDefaultTheme(); 
        $d              = isset($_index_theme) ? $_index_theme : $this->getIndexThemes(); 
        $t_root         = $this->getRootTheme() . '/' . $t . '/' . $d;
        foreach($this->extensions as $ext){
            if(file_exists($t_root . $ext)){
                $theme_root_exist = $t_root . $ext;
            }
        }
        return $theme_root_exist;
    }
    
    private function html($_view = null){
        $url_view = $this->urlViewRoot(is_array($_view)? $_view['view'] : $_view);
        if($url_view){ 
            $htmlView = $this->file_get_html($url_view);
            if($this->getBlockView()){
                $htmlView->find($this->getBlockView(), 0);
            }
        }
        $url_theme = $this->urlThemeRoot($this->getTheme(), $this->getIndexTheme());
        if($url_theme){
            $this->html = $this->file_get_html($url_theme);
            if($this->getBlockTheme()){
                $this->html->find($this->getBlockTheme(), 0);
            }
        }
        if($this->getViewTheme() AND $this->html){
            foreach ($this->html->find($this->getViewTheme()) as $element){
                $element->innertext = $htmlView;            
            }
        }else{
            $this->html = $htmlView;
        }
        
    }
            
    public function view($_view = null) {
        $this->html($_view);
        $this->innerText($this->html);
        $this->urlRefactoring();
        echo $this->html;
    }

}
