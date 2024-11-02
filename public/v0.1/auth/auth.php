<?php
require_once __DIR__ ."/../../../api_core/query.php";

$requisicao = file_get_contents('php://input'); #Atribui na variável qualquer json que tenha sido enviado em uma requisição

$data = json_decode($requisicao, true); #Da decode no json e esse true ai serve pra virar um array associativo certinho ja

if(!empty($data)){
    $user = $data['user'];
    $senha = $data['password'];
    #Query::Send("SELECT * FROM USUARIOS WHERE USUARIO = '$user' AND SENHA = '$senha'"); #Essa linha que vai mandar a solicitação pro banco e retornar algo se tiver certo
    echo "SELECT * FROM USUARIOS WHERE USUARIO = $user AND SENHA = $senha";              #Botei echo só pra testar msm mas vai ser mandado pro response dai pra devolver alguma coisa
}                                                                                        #Na reunião boto pra funcionar isso agota tem que trabaia
else{
    echo "Deu ruim";
}




?>