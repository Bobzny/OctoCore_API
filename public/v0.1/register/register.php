<?php
require_once __DIR__ ."/../../../api_core/query.php";
require_once __DIR__ ."/../../../api_core/response.php";

$requisicao = file_get_contents('php://input'); #Atribui na variável qualquer json que tenha sido enviado em uma requisição

$data = json_decode($requisicao, true);


if(!empty($data)){
    #$resultado = Query::Send("SELECT * FROM USUARIOS WHERE USUARIO = 'bobzito' AND SENHA = '123'"); 

    $user = $data['user'];
    $pw = $data['password'];
    $resultado = Query::Send("INSERT INTO USUARIOS VALUES (null, '$user', '$pw', default, 0)"); 
    if ($resultado){
        
        echo Response::geison($resultado);
    }
    #echo ("INSERT INTO USUARIOS VALUES (null, '$user', '$pw', default");
}                                                                                       
else{
    echo "Sem JSON na requisição >:C";
}


?>