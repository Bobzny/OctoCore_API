<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";

$requisicao = json_decode(file_get_contents('php://input'), true); #Da decode no json e esse true ai serve pra virar um array associativo certinho ja

if(!empty($requisicao)){ #Verifica se algum dado foi recebido

    if (isset($requisicao['email']) && isset($requisicao['password'])){ #verifica se o JSON possui os campos necessários
        
        $params = [$requisicao['email']];
        
        $resultados = Query::Send("SELECT * FROM USUARIOS WHERE EMAIL = ?",$params); 
        
        if (is_array($resultados[1])){
            if (password_verify($requisicao['password'], $resultados[1][0]['senha'])){
                $retorno = [    "autenticado" => True, "linkPFP" => $resultados[1][0]['linkPFP'],
                                "idUsuario" => $resultados[1][0]['idUsuario'], "usuario" => $resultados[1][0]['usuario'],
                                "email" => $resultados[1][0]['email']
                            ];
                echo Response::Enviar(200,$retorno);
            }
            else{
                echo Response::Enviar(401,"Credenciais inválidas");
            }

        }
        else{
            echo Response::Enviar(404,"Credenciais inválidas");
        }
        

    }
    else{
        echo Response::Enviar(400,"Requisição inválida");
    }

}                                                                                       
else{
    echo Response::Enviar(400,"Sem JSON na requisição >:C");
}




?>