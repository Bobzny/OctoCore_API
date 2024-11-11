<?php
require_once __DIR__.'./conexao.php';
require_once __DIR__.'./query.php';

function checarEstoque($conteudo){

#ESSE TA TENEBROSO AINDA
}

function createOder($valorTotal, $metodoPagamento, $desconto, $userID){

    Query::Send("INSERT INTO PEDIDOS VALUES (null, 'Criado', $valorTotal, '$metodoPagamento', 10, NOW(),$userID)");


}
function updateOrder(){


}
function statusOrder(){


}




?>