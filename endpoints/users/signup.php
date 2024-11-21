<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";

$requisicao = json_decode(file_get_contents('php://input'), true); #Atribui na variável qualquer json que tenha sido enviado em uma requisição


if(!empty($requisicao)){
    #verifica se o JSON possui os campos necessários
    if (isset($requisicao['user']) && isset($requisicao['email']) && isset($requisicao['password']) && isset($requisicao['linkImagem'])){ 
        $param = [$requisicao['user'], $requisicao['email'], $requisicao['password'], $requisicao['linkImagem']];
        
        #É importante os parâmetros estarem na ordem certa igual no comando SQL abaixo 
        $resultado = Query::Send("  INSERT INTO usuarios (nivelAcesso, usuario, email, senha, linkImagem) VALUES 
                                    (default, ?, ?, ?, ?)", $param);  

        if (is_array($resultado)){

            echo Response::geison($resultado[1]);
            die;
        }
        else{
            echo Response::geison($resultado);
        }

    }
    else{
        echo "Requisição inválida";
    }
    
}                                                                                       
else{
    echo "Sem JSON na requisição :C";
}


?>