<?php

class CW_View {
    private $html           = null; /*html geral*/
    private $innerText      = null; /*vai armazenar todos os valores que serao utilizados no html*/
    private $config         = null; /*arquivo de configuração*/
    private $extensions     = array('.php', '.html'); /*Extenções que ira buscar para montar views e themes*/
    private $view           = null; /*view selecionada*/
    private $blockView      = null; /*bloco a ser utilizado da view*/
	private $rootView		= null; /*local root das views*/
    //private $theme        = null; /*thema selecionado*/
	private $defaultTheme 	= null; /*Theme selecionado*/
    private $blockTheme     = null; /*bloco a ser utilizado do theme*/
    private $viewTheme      = null; /*bloco no theme onde sera montado a view*/
    private $indexTheme     = null; /*arquivo index do theme*/
	private $rootTheme 		= null; /*Caminho root dos themes*/
    
    public function __construct() {
        $this->config = new Config();
    }
	
	function getConfig() {
        return $this->config;
    }
	function setConfig($config) {
        $this->config = $config;
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
        return ($this->view)? $this->view : $this->config->getIndexViews();
    }
	function setView($view) {
        $this->view = $view;
    }

    function getBlockView() {
        return isset($this->blockView)? $this->blockView : $this->config->getBlockViews();
    }
	function setBlockView($blockView) {
        $this->blockView = $blockView;
    }
	
	function getRootView() {
        return isset($this->rootView)? $this->rootView : $this->config->getRootViews();
    }
	function setRootView($rootView) {
		$getLocalRoot = $this->config->getLocalRoot();
        $this->rootView = CW_util::documentRoot() . '/' . isset($getLocalRoot)? $getLocalRoot . '/' : '' . $rootView;
    }

    function getTheme() {
        return $this->getDefaultTheme();
    }
	function setTheme($theme) {
        $this->setDefaultTheme($theme);
    }
	
	function getDefaultTheme() {
        return isset($this->defaultTheme)? $this->defaultTheme : $this->config->getDefaultThemes();
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
        return isset($this->viewTheme)? $this->viewTheme : $this->config->getViewThemes();
    }
	function setViewTheme($viewTheme) {
        $this->viewTheme = $viewTheme;
    }

    function getIndexTheme() {
        return isset($this->indexTheme)? $this->indexTheme : $this->config->getIndexThemes();
    }
    function setIndexTheme($indexTheme) {
        $this->indexTheme = $indexTheme;
    }
	
	function getRootTheme() {
        return isset($this->rootTheme)? $this->rootTheme : $this->config->getRootThemes();
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
        if($this->config->getUrlRefactoring()){
            foreach($finds as $k => $v){
				foreach ($this->html->find($k) as $element) {
					if (!strstr($element->$v, 'http')) {
						$element->$v = $this->config->getServerThemes() . $this->config->getDefaultThemes() . '/' . $element->$v;
					}
				}
            }
        }
        return $this;
    }
    
    private function urlViewRoot($_view = null, $view_root_exist = null){
        $view      = isset($_view) ? $_view : $this->getView();
        $view_root = $this->getRootView() . $view;
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
    private function urlThemeRoot($_theme = null, $_index_theme = null, $t_root_exist = null){
        $extensions     = array('.php', '.html');
        $t              = isset($_theme) ? $_theme : $this->getDefaultTheme(); 
        $d              = isset($_index_theme) ? $_index_theme : $this->getIndexThemes(); 
        $t_root         = $this->getRootTheme() . $t . '/' . $d;
        foreach($extensions as $ext){
            if(file_exists($t_root . $ext)){
                $t_root_exist = $t_root . $ext;
            }
        }
        return $t_root_exist;
    }
    
    private function html($_view = null){
        $view           = is_array($_view)? $_view['view'] 						: $_view;
        $block_view     = isset($_view['block_view'])? $_view['block_view'] 	: $this->getBlockView();
        $theme          = isset($_view['theme'])? $_view['theme'] 				: $this->getTheme();
        $block_theme    = isset($_view['block_theme'])? $_view['block_theme'] 	: $this->getBlockTheme();  
        $view_theme     = isset($_view['view_theme'])? $_view['view_theme'] 	: $this->getViewTheme(); 
        $index_theme    = isset($_view['index_theme'])? $_view['index_theme'] 	: $this->getIndexTheme();        
        /*Busca a view */
		$url_view = $this->urlViewRoot($view);
		if(empty($url_view)){
			$url_view = $this->urlViewRoot(null);
		}
        $_htmlView      = file_get_html($url_view);
        /*Busca o bloco html da view */
        $this->html     = $htmlView = isset($block_view)? $_htmlView->find($block_view, 0): $_htmlView;
        /*Busca a url do theme se existir*/
        $url_theme      = $this->urlThemeRoot($theme, $index_theme);		
        $htmlTheme = isset($url_theme)? file_get_html($url_theme) : null; 
        if($htmlTheme){
            /*Busca o bloco do theme se existir*/
            $this->html = isset($block_theme)? $htmlTheme->find($block_theme, 0) : $htmlTheme;
        }
        /*monta a view no theme */
        if($view_theme and $htmlTheme){
            foreach ($this->html->find($view_theme) as $element){
                $element->innertext = $htmlView;            
            }
        }
    }
            
    public function view($_view = null) {
        $this->html($_view);
        $this->innerText($this->html);
        $this->urlRefactoring();
        echo $this->html;
    }

}
