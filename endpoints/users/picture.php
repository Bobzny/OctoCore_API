<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";


if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    if (isset($requisicao['idUsuario']) && isset($requisicao['linkPFP'])){ # Alterar imagem de perfil
        $params = [$requisicao['linkPFP'], $requisicao['idUsuario']];
        $sql = "UPDATE usuarios SET linkPFP = ? WHERE idUsuario = ?";

        $resultados = Query::Send($sql, $params);
        if($resultados[0] === 200){
            echo Response::Enviar($resultados[0], "Imagem de perfil alterada com sucesso");
        }
        else{
            echo Response::Enviar($resultados[0], "Erro na alteração da imagem");
        }
    }
    else if(isset($requisicao['idUsuario']) && $requisicao['linkPFP'] === null){ # Resetar imagem de perfil pra default
        $params = [$requisicao['idUsuario']];
        $sql = "UPDATE usuarios SET linkPFP = DEFAULT WHERE idUsuario = ?";

        $resultados = Query::Send($sql, $params);
        if($resultados[0] === 200){
            echo Response::Enviar($resultados[0], "Imagem de perfil removida com sucesso");
        }
        else{
            echo Response::Enviar($resultados[0], "Erro na remoção da imagem");
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