<?php
//Arquivo central para roteamento, sanitizando todas as requisições que entram no servidor antes de realizar qualquer ação e seguindo o modelo mvc
$requisicao = json_decode(file_get_contents('php://input'), true);
require_once __DIR__.'/helpers/autoload.php';
//Encerrar o código caso nao seja um GET e não tenha requisição
if(empty($requisicao) && $_SERVER['REQUEST_METHOD'] != 'GET'){
    echo Response::json(400, "Sem corpo na requisição :C");
    die;
}



$uri = $_SERVER['REQUEST_URI'];
//echo $url;
$params = explode('/',trim($uri, '/'));


require_once __DIR__.'/helpers/response.php';
echo Response::json(200, $params);
require_once __DIR__.'/routes/routes.php';
$rota = Routes::getRoute($_SERVER['REQUEST_METHOD'], $params);

//require_once __DIR__.'/controllers/'.$params[1].'/'.$params[2].'.php';
//$teste = call_user_func(['AuthController', 'Post'], $requisicao);
//echo Response::json(200, $teste);
//require_once __DIR__.'/controllers/users/auth.php';
?>