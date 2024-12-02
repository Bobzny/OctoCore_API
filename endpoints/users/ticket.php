<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    if (isset($requisicao['idUsuario']) && isset($requisicao['idPedido']) && isset($requisicao['titulo']) && isset($requisicao['descricao'])){
        $params = [$requisicao['idUsuario'], $requisicao['idPedido'], $requisicao['titulo'], $requisicao['descricao']];
        $sql = "INSERT INTO tickets (idUsuario, idPedido, titulo, descricao) 
                VALUES (?, ?, ?, ?)";

        $resultados = Query::Send($sql, $params);
        if($resultados[0] === 200){
            echo Response::Enviar($resultados[0], "Ticket com ID ".$resultados[2]." criado com sucesso");
        }
        else{
            echo Response::Enviar($resultados[0], "Erro na criação do ticket");
        }
    }
    else{
        echo Response::Enviar(400, "Requisição inválida");
    }
}
else{
    echo Response::Enviar(405, "Método não suportado");
}

?>