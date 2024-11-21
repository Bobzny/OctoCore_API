<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";


$requisicao = json_decode(file_get_contents('php://input'), true);; #Atribui na variável qualquer json que tenha sido enviado em uma requisição

if(!empty($requisicao)){ #Verifica se algum dado foi recebido

    if (isset($requisicao['user']) && isset($requisicao['password'])){ #verifica se o JSON possui os campos necessários

        $params = [$requisicao['user'], $requisicao['password']];
        
      
        $resultado = Query::Send("SELECT * FROM USUARIOS WHERE USUARIO = ? AND SENHA = ?",$params); 
        if (is_array($resultado)){
            echo Response::geison(["autenticado" => True, "nivelAcesso" => $resultado[0]['nivelAcesso'], "linkImagem" => $resultado[0]['linkImagem']]);
        }
        else{
            echo Response::geison(False);
        }


    }
    else{
        echo "Requisição inválida";
    }

}                                                                                       
else{
    echo "Sem JSON na requisição >:C";
}




?>