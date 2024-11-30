<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";

$requisicao = json_decode(file_get_contents('php://input'), true); #Atribui na variável qualquer json que tenha sido enviado em uma requisição

function Post(){
        if (isset($requisicao['numeroCC']) && isset($requisicao['cvv']) && isset($requisicao['validade']) && isset($requisicao['idUsuario'])){ #verifica se o JSON possui os campos necessários
            $dividido = explode("-", $requisicao['numeroCC']);
            $finalCC = end($dividido); #Armazena os 4 últimos números 
            $numeroCC = password_hash($requisicao['numeroCC'], PASSWORD_BCRYPT);
            $cvv = password_hash($requisicao['cvv'], PASSWORD_BCRYPT);
            $params = [$numeroCC, $cvv, $requisicao['validade'], $finalCC, $requisicao['idUsuario']];
           
            $resultados = Query::Send("INSERT INTO cartoes (numeroCC, cvv, validade, final, idUsuario) VALUES 
                                        (?, ?, ?, ?, ?)", $params);  
    
        }
        else{
            return [400,"Requisição inválida"];
        }
        

}
function Delete(){
    #Ainda não implementado
}

switch ($_SERVER['REQUEST_METHOD']){
    case 'POST':
        $resultados = Post();
        break;
    case 'DELETE':
        $resultados = Delete();
        break;
}
echo Response::Enviar($resultados[0], $resultados[1]);
?>