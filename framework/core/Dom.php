<?php

namespace framework\core;

use framework\library\simple_html_dom;

class Dom {
    
    private $html = null;
    private $element = array();
    
    function __construct() {

    }
    
    function getHtml() {
        return $this->html;
    }

    function getElement() {
        return $this->element;
    }

    function setHtml($html) {
        $this->html = isset($html)? $this->file_get_html($html) : null;
    }
    
    /**
     * Seta elemtos para o html
     * array('.selector' => array('innertext' => 'value', 'src' => 'img.jpg'));
     * @param type $element
     */
    function setElement($element) {
        $this->element = $element;
        foreach($this->element as $e){
            $this->changeHtml($e);
        }
    }
    
    private function changeHtml($e = null){
        $selector   = $e[0];
        $values     = $e[1];
        foreach($values as $attribute  => $value){
            foreach($this->html->find($selector) as $element){
                $element->$attribute = $value;
            }
        }
    }
        
    // get html dom from url
    public function file_get_html($url, $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = 'UTF-8', $stripRN=true, $defaultBRText="\r\n", $defaultSpanText=" "){
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
        $contents = file_get_contents($url, $use_include_path, $context, $offset);
        if (empty($contents) || strlen($contents) > 600000) {
            return false;
        }
        $dom->load($contents, $lowercase, $stripRN);
        $this->html = $dom;
    }

    // get html dom from string
    public function str_get_html($str, $lowercase=true, $forceTagsClosed=true, $target_charset = 'UTF-8', $stripRN=true, $defaultBRText="\r\n", $defaultSpanText=" ") {
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
        if (empty($str) || strlen($str) > 600000){
            $dom->clear();
            return false;
        }
        $dom->load($str, $lowercase, $stripRN);
        $this->html = $dom;
    }

    // dump html dom tree
    function dump_html_tree($node, $show_attr=true, $deep=0)
    {
        $node->dump($node);
    }
    
    
    
    
    
    
}
