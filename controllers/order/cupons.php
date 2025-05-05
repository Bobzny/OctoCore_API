<?php
require_once __DIR__ ."/../../models/query.php";
//require_once __DIR__ ."/../../core/response.php";

// if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cupom'])){
//     $params = [$_GET['cupom']];
//     $resposta = Query::Send("SELECT * FROM CUPONS WHERE CODIGO = ?",$params);
//     if ($resposta[0] === 200 && $resposta[1][0]['isActive'] === 1){
//         echo Response::Enviar(200, $resposta[1][0]['desconto']);
//     }
//     else{
//         echo Response::Enviar(404, "Cupom inválido");
//     }

// }
// else{
//     echo Response::Enviar(400, "Requisição inválida");
// }

class Cupons{
    public static function main($params){
        
    }
}



?>