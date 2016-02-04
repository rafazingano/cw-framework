<?php

class CW_DB {
    private $db         = null;
    private $query      = null;
    private $select     = null;
    private $from       = null;
    private $join      = null;
    private $limit      = null;
    private $where      = null;
    private $return     = null;
    private $config_db  = 'default';

    public function __construct() {
        
    }

    public function set_config($c_db = 'default') {
        $this->config_db = $c_db;
        return $this;
    }

    public function create() {
        if (CW_config::$db[$this->config_db]['driver'] == 'mysqli') {
            $mysqli = new mysqli(
                    Config::$db[$this->config_db]['server'], 
                    Config::$db[$this->config_db]['user'], 
                    Config::$db[$this->config_db]['pass'], 
                    Config::$db[$this->config_db]['db']
            );
            if (CW_config::$db[$this->config_db]['charset'] != '') {
                $mysqli->set_charset(CW_config::$db[$this->config_db]['charset']);
            }
            return $mysqli;
        }
    }

    public function select($s = '*') {
        $this->select = $s;
        return $this;
    }

    public function from($f = null) {
        $this->from = $f;
        return $this;
    }

    public function limit($l = null) {
        $this->limit = $l;
        return $this;
    }
    
    public function join($table = null, $condition = null, $type = 'INNER'){
        $this->join .= ' ' . $type . ' JOIN ' . $table . ' ON ' . $condition . ' ';
        return $this;
    }

    public function where($w = null) {
        if(!is_array($w)){
            $this->where .= $w;
        }else{
            $this->wh = array();
            foreach ($w as $k => $v) {
                $this->wh[] = "{$k} = '{$v}'";
            }
            if (count($this->wh) > 0){
                $this->where .= ' ' . implode(' AND ', $this->wh) . ' ';
            }
        }
        return $this;
    }
    
    public function query($q = null){
        if($q){
            $this->query = $q;
        }else{
            if($this->select){
                $this->query .= ' SELECT ' . $this->select;
            }
            if($this->from){
                $this->query .= ' FROM ' . $this->from;
            }
            if($this->join){
                $this->query .= ' ' . $this->join;
            }
            if($this->where){
                $this->query .= ' WHERE ' . $this->where;
            }

            if($this->limit){
                $this->query .= ' LIMIT ' . $this->limit;
            }
        }
        return $this;
    }

    public function result($resulttype = MYSQLI_ASSOC) {
        if (!$this->query) {
            $this->query();
        }
        if ($this->query) {
            $this->db = $this->create();
            $r = $this->db->query($this->query);
            if($r){
                $this->return = $r->fetch_all($resulttype);
                $r->free();
                //$r->close();
            }            
            $this->db->close();
        }
        return $this->return;
    }

}

?>