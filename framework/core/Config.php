<?php

class CW_Config{
    protected $localRoot          = null;
    protected $rootViews          = null;
    protected $serverViews        = null;
    protected $rootThemes         = null;
    protected $serverThemes       = null;
    protected $defaultThemes      = null;
    protected $indexThemes        = null;
    protected $viewThemes         = null;
    protected $urlRefactoring     = null; 
    protected $indexViews         = null;
    protected $localControllers   = null;
    protected $blockViews         = null;

    protected $inner = array(
        'title'         => 'My CW Framework',
        'attributes'    => array(
            'meta[name="description"]' => array(
                'content' => 'My CW Framework'
            ),
            'meta[name="author"]' => array(
                'content' => 'My CW Framework'
            )
        )
    );
    protected $config = array(
        'url_refactoring' 	=> true,
		'debug' 			=> true
    );    
    protected $db = array(
        'default' => array(
            'server' => 'servidor_default',
            'user' => 'usuario_default',
            'driver' => 'mysqli',
            'pass' => 'senha_default',
            'port' => '',
            'db' => 'tabela_default',
            'charset' => 'utf-8'
        ),
        'localhost' => array(
            'server' => 'localhost',
            'user' => 'root',
            'driver' => 'mysqli',
            'pass' => '',
            'port' => '',
            'db' => 'banco',
            'charset' => 'utf-8'
        )
    );    
    /*
     * local_root = para o caso do site estar dentro de um subdiretorio que nao na rais
     * local_views = local onde ficarao as views 
     * local_themes = caso haja um layout padrao, se nao for setado a view fica tambem sendo o layou
     * index_view = a view padrão para quando nao for setada nenhuma
    */
    protected $structure = array(
        'local_root'        => 'cw-framework',		/*Diretorio raiz do framework*/
        /*Configurações das controllers*/
        'local_controllers' => 'controllers',		/*Diretório das controllers*/
        'index_controllers' => 'index',				/*arquivo default controller*/
        /*Configurações das views*/
        'local_views'       => 'views',				/*Diretório das views*/
        'index_views'       => 'default/index',		/*arquivo default view*/
		'block_views' 		=> null,				/*Caso não vá usar todo o views busca-se o bloco a ser utilizado.*/
        /*Configurações dos themes*/
        'local_themes'      => 'themes', 			/*Diretório onde ficaram o(s) theme(s)*/
        'default_themes'    => 'default', 			/*Theme padrão quando nenhum for setado na controller*/
        'index_themes'      => 'index', 			/*Arquivo index do theme, pode acontecer de ter mais arquivos no diretorio do theme e ou ser alterado o index do theme*/
        'view_themes'       => 'div[class="mastfoot"]', /*Bloco onde vai ser inserido a view*/
		'block_theme' 		=> null 				/*Caso não vá usar todo o theme busca-se o bloco a ser utilizado.*/
    );
    
    public function __construct() {
        $this->setUrlRefactoring(isset($this->config['url_refactoring']) ? $this->config['url_refactoring'] : null);
        $this->setLocalRoot(isset($this->structure['local_root']) ? $this->structure['local_root'] : null);
        $this->setRootViews(isset($this->structure['local_views']) ? $this->structure['local_views'] : null);
        $this->setServerViews(isset($this->structure['local_views']) ? $this->structure['local_views'] : null);
        $this->setIndexViews(isset($this->structure['index_views']) ? $this->structure['index_views'] : null);
        $this->setRootThemes(isset($this->structure['local_themes']) ? $this->structure['local_themes'] : null);        
        $this->setServerThemes(isset($this->structure['local_themes']) ? $this->structure['local_themes'] : null);
        $this->setDefaultThemes(isset($this->structure['default_themes']) ? $this->structure['default_themes'] : null); 
        $this->setViewThemes(isset($this->structure['view_themes']) ? $this->structure['view_themes'] : null); 
        $this->setIndexThemes(isset($this->structure['index_themes']) ? $this->structure['index_themes'] : null); 
        $this->setLocalControllers(isset($this->structure['local_controllers']) ? $this->structure['local_controllers'] : null);
    }
    
    function getInner() {
        return $this->inner;
    }

    function setInner($inner) {
        $this->inner = $inner;
    }
    
    public function setLocalRoot($r = null) {
        $this->localRoot = $r;
    }  
	public function getLocalRoot() {
        return $this->localRoot;
    }	
	
    public function setUrlRefactoring($lr = null){
        $this->urlRefactoring = $lr;
    }
    public function getUrlRefactoring(){
        return $this->urlRefactoring;
    }   
    /*
     * Metodos de controllers
     */
    public function setLocalControllers($c = null){
        $this->localControllers = $c;
    }
    public function getLocalControllers(){
        return $this->localControllers;
    }
    /*
     * Metodos das views
     */
    public function setRootViews($v = null) {
        $this->rootViews = CW_util::documentRoot();
        //$this->rootViews .= isset($this->localRoot)? $this->localRoot .  '/' : ''; 
        $this->rootViews .= isset($v)? $v . '/' : '';
    }
	public function getRootViews() {
        return $this->rootViews;
    }
	
	public function getBlockViews(){
        return $this->blockViews;
    }
    public function setBlockViews($b = null){
        $this->blockViews = isset($b)? $b : null;
    }
	
    public function getIndexViews(){
        return $this->indexViews;
    }
    public function setIndexViews($iv){
        $this->indexViews = isset($iv)? $iv : null;
    }
        
    public function setServerViews($v = null) {
        $this->serverViews = CW_util::serverName(true) 
            //. isset($this->localRoot)? $this->localRoot .  '/' : '' 
            . isset($v)? $v . '/' : '';
    }    
    /*
     * Metodos de themes
    */
    public function setDefaultThemes($t){
        $this->defaultThemes = isset($t)? $t : null;
    }
    public function getDefaultThemes(){
        return $this->defaultThemes;
    }    
    public function setRootThemes($t = null) {
        $this->rootThemes = CW_util::documentRoot();
        $this->rootThemes .= isset($t)? $t . '/' : '';
    }    
    public function getRootThemes(){
        return $this->rootThemes;
    }    
    public function setServerThemes($t = null) {
        $root       = CW_util::serverName(true);
        $server_theme = isset($t)? $t . '/' : '';
        $this->serverThemes = $root . $server_theme;
    }
    public function getServerThemes() {
        return $this->serverThemes;
    }
    function setIndexThemes($indexThemes) {
        $this->indexThemes = $indexThemes;
    }
    function getIndexThemes() {
        return $this->indexThemes;
    }   
    function getViewThemes() {
        return $this->viewThemes;
    }

    function setViewThemes($viewThemes) {
        $this->viewThemes = $viewThemes;
    }


}