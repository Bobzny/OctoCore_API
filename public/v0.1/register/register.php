<?php
require_once __DIR__ ."/../../../api_core/query.php";
require_once __DIR__ ."/../../../api_core/response.php";

$requisicao = file_get_contents('php://input'); #Atribui na variável qualquer json que tenha sido enviado em uma requisição

$data = json_decode($requisicao, true);


if(!empty($data)){
    try{

        $user = $data['user'];
        $pw = $data['password'];


    }finally{
        die("Requisição inválida!");
    }


    $resultado = Query::Send("INSERT INTO USUARIOS VALUES (null, '$user', '$pw', default, 0)"); 
    echo Response::geison($resultado);
    
   
}                                                                                       
else{
    echo "Sem JSON na requisição :C";
}


?>