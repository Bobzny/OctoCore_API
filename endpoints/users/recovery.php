<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";

$requisicao = json_decode(file_get_contents('php://input'), true);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    if(isset($requisicao['email']) && isset($requisicao['user'])){
        $sql = "SELECT * FROM usuarios WHERE email = ? AND usuario = ?";
        $params = [$requisicao['email'], $requisicao['user']];
        $resposta = Query::Send($sql, $params);
        if($resposta[0] === 200){
            echo Response::Enviar($resposta[0], "Ta enviado no seu e-mail confia");
        }
        else{
            echo Response::Enviar($resposta[0], "Achei não amigo");
        }
    }   

}
else{
    echo Response::Enviar(405, "Requisição inválida");
}

?>