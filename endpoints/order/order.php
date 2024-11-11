<?php

require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";


$valorTotal = 2400.99;
$metodoPagamento = '4561-4652-6418-3124';
$userID = 1;

$requisicao = file_get_contents('php://input');


#TEM Q MEXER BASTANTE AINDA
    $idPedido = $_GET['id'];
    $resposta = Query::Send("SELECT * FROM PEDIDOS WHERE idPedido = $idPedido");
    echo Response::geison($resposta);




?>