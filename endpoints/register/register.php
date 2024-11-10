<?php
require_once __DIR__ ."/../../api_core/query.php";
require_once __DIR__ ."/../../api_core/response.php";

$requisicao = file_get_contents('php://input'); #Atribui na variável qualquer json que tenha sido enviado em uma requisição

$data = json_decode($requisicao, true);


if(!empty($data)){

    if (isset($data['user']) && isset($data['password'])){ #verifica se o JSON possui os campos necessários

        $user = $data['user'];
        $pw = $data['password'];
        #Isso tem que ser mudado pra um prepared statement no futuro pra não ter injeção sql, por enquanto serve
        $resultado = Query::Send("INSERT INTO USUARIOS VALUES (null, '$user', '$pw', default, 0)");  
        echo Response::geison($resultado); 

    }
    else{
        echo "Requisição inválida";
    }
    
}                                                                                       
else{
    echo "Sem JSON na requisição :C";
}


?>