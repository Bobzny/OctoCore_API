<?php
//Arquivo central para roteamento, sanitizando todas as requisições que entram no servidor antes de realizar qualquer ação e seguindo o modelo mvc

$url = $_SERVER['REQUEST_URI'];
//echo $url;
//print_r(explode('/',$url));
//print_r(explode('/',trim($url, '/')));


require_once __DIR__.'/helpers/response.php';
echo Response::json(200);

?>