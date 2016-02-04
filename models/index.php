<?php 
/**
 * Classe modelo de usuário. tem o
 * objetivo de conectar ao banco de dados
 * recuperar, inserir, alterar e apagar os
 * dados dos usuários existentes lá.
 */
class Usuario  {

    /**
     * Método criado para listar os usuários
     * existentes na tabela de usuários do
     * banco de dadoss.
     */
    public function listar($condicoes = array()) {
    	# cria uma conexão usando a configuração
    	# "padrao" da classe Config em config.php
    	$db = DB::criar('padrao');

    	# começa a montar o select
    	$sql = "select * from usuarios ";

    	# monta o Where de acordo com a
    	# lista de condições. Funciona 
    	# apenas com o operador =.
    	# Você pode melhorar isso... :D
    	$where = array();
    	foreach($condicoes as $campo => $valor) {
		  $where = "{$campo} = {$valor}";
    	}

    	if ($where != array()) {
		  $where = " where " . implode(' and ', $where);
    	}
    	else {
		  $where = '';
    	}
    	# termina de montar o SQL
    	$sql .= $where;

    	# executa o SQL e retorna a lista de usuarios
    	$resultado = $db->query($sql);
    	$lista = $resultado->fetch_all(MYSQLI_ASSOC);
    	$resultado->free();

    	return $lista;
    }

    /**
     * Método criado para encontrar um
     * usuário usando seu ID. Usa o 
     * método listar para isso.
     */
    public function encontrar($id) {
        $condicao = array('id' => $id);
        $item = self::listar($condicao);
        return $item[0];
    }

    /* aqui você criaria outros métodos
      como inserir, salvar e apagar, 
      que não entram em nosso exemplo */

}

?>