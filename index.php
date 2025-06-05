<?php
//Arquivo central para roteamento, sanitizando todas as requisições que entram no servidor antes de realizar qualquer ação e seguindo o modelo mvc
//Tratamento da requisição para caso não seja um json
$requisicao = json_decode(file_get_contents('php://input'), true) ?? [];
require_once __DIR__.'/helpers/autoload.php';
//Encerrar o código caso nao seja um GET e não tenha requisição
if(empty($requisicao) && empty($_FILES) && $_SERVER['REQUEST_METHOD'] != 'GET'){
    echo Response::json(400, "Sem corpo na requisição :C");
    die;
}


//Pega tudo que foi passado na url para validar a rota
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$params = explode('/',trim($uri, '/'));
$paramsRota = array_slice($params,1);



require_once __DIR__.'/helpers/response.php';
require_once __DIR__.'/routes/routes.php';
//Preparação de rotas e parâmetros
//Para rotas com apenas um parâmetro, como o GET de produtos e criação de pedidos
if(sizeof($paramsRota) == 1){
    $caminho = $paramsRota[0];
    $parametros = [];
}
else if(sizeof($paramsRota) == 2 && $_SERVER['REQUEST_METHOD'] == 'GET'){
    //Tratamento para os gets de usuário que tem dois parâmetros, mas são ambos de rota
    if ($paramsRota[0] == 'user'){
        $caminho = $paramsRota[0] . '/' .$paramsRota[1];
        $parametros = []; 
    }
    //Outros GETs que tem dois parâmetros, mas são de rota e um parâmetro
    else{
        $caminho = $paramsRota[0];
        $parametros[] = $paramsRota[1]; // GET com um parâmetro
    }
}
//Para as rotas com dois parametros de caminho
else if (sizeof($paramsRota) == 2){
    $caminho = $paramsRota[0] . '/' .$paramsRota[1];
}
else{
    echo Response::json(400, "Requisição inválida");
    die;
}
//Procura a rota correspondente ao caminho e método HTTP
$rota = Routes::getRoute($_SERVER['REQUEST_METHOD'], $caminho);
//Encerra o código caso a rota não exista
if (!$rota) {
    echo Response::json(404, "Rota não encontrada");
    die;
}

//Verifica se a rota é protegida
if ($rota && $rota[2] === true) {
    //Caso seja protegida, verifica se o token JWT foi enviado e é válido
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        echo Response::json(401, "Token não enviado");
        die;
    }
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $dados = Jwt::Validar($token);
    if (!$dados) {
        echo Response::json(401, "Token inválido ou expirado");
        die;
    }
    // Adiciona o ID do usuário ao array de parâmetros ou a requisição
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $parametros[] = $dados['idUsuario']; // Adiciona o ID do usuário autenticado aos parâmetros
    }
    else {
        $requisicao['idUsuario'] = $dados['idUsuario']; // Adiciona o ID do usuário autenticado à requisição
    }  
}
//Chama a função correspondente à rota
//Se for GET, chama a função com os parâmetros, se não, chama com a requisição
if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    $resposta = call_user_func([$rota[0], $rota[1]], $parametros);
}
else{
    $resposta = call_user_func([$rota[0], $rota[1]], $requisicao);
}
//Devolve a resposta em JSON
echo Response::json(...$resposta);


?>