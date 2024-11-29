<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";

$requisicao = json_decode(file_get_contents('php://input'), true); #Atribui na variável qualquer json que tenha sido enviado em uma requisição

if(!empty($requisicao)){

    if (isset($requisicao['user']) && isset($requisicao['email']) && $requisicao['password']){ #verifica se o JSON possui os campos necessários
        $hash = password_hash($requisicao['password'], PASSWORD_BCRYPT);
        print_r($hash);
        $params = [$requisicao['user'], $requisicao['email'], $hash];
        #Isso tem que ser mudado pra um prepared statement no futuro pra não ter injeção sql, por enquanto serve
        $resultados = Query::Send("  INSERT INTO usuarios (usuario, email, senha) VALUES 
                                    (?, ?, ?)", $params);  
        echo Response::Enviar(...$resultados); 

    }
    else{
        echo Response::Enviar(400,"Requisição inválida");
    }
    
}                                                                                       
else{
    echo Response::Enviar(400,"Sem JSON na requisição >:C");
}


?>