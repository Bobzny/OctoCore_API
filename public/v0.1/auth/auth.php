<?php
require_once __DIR__ ."/../../../api_core/query.php";
require_once __DIR__ ."/../../../api_core/response.php";


$requisicao = file_get_contents('php://input'); #Atribui na variável qualquer json que tenha sido enviado em uma requisição

$data = json_decode($requisicao, true); #Da decode no json e esse true ai serve pra virar um array associativo certinho ja

if(!empty($data)){
    #$resultado = Query::Send("SELECT * FROM USUARIOS WHERE USUARIO = 'bobzito' AND SENHA = '123'"); 

    $user = $data['user'];
    $pw = $data['password'];
    $resultado = Query::Send("SELECT * FROM USUARIOS WHERE USUARIO = '$user' AND SENHA = '$pw'"); 
    echo Response::geison($resultado);
}                                                                                       
else{
    echo "Sem JSON na requisição >:C";
}




?>