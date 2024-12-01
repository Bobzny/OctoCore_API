<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";

function Post(){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    if(isset($requisicao['nome']) && isset($requisicao['cep']) && isset($requisicao['rua']) && isset($requisicao['complemento']) && isset($requisicao['idUsuario'])){
        $params = [$requisicao['nome'], $requisicao['cep'], $requisicao['rua'], $requisicao['complemento'], $requisicao['idUsuario']];
        $sql = "INSERT INTO enderecos (nome, cep, rua, complemento, idUsuario) VALUES (?, ?, ?, ?, ?)";
        $inserir = Query::Send($sql, $params);
        if ($inserir[0] === 200){
            return [$inserir[0], $inserir[1]];
        }
        else{
            return[400, "Cadastro de endereço falhou: ".$inserir[1]];
        }

    }
    else{
        return [400, "Requisição inválida"];
    }

}
function Get(){
    if (isset($_GET['user'])){
        $params = [$_GET['user']];
        $sql = "SELECT * FROM enderecos WHERE idUsuario = ?";
        $resultados = Query::Send($sql, $params);
        return $resultados;
    }
    else{
        return [400, "Sem ID de usuário :c"];
    }
    
}
function Delete(){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    if (isset($requisicao['idEndereco'])){
        $params = [$requisicao['idEndereco']];
        $sql = "DELETE FROM enderecos WHERE idEndereco = ?";
        $resultados = Query::Send($sql,$params);
        if ($resultados[0] === 200){
            return $resultados;
        }
        else{
            return[400, "Exclusão de endereço falhou: ".$resultados[1]];
        }
    }
    else{
        return [400, "ID do endereço inválido"];
    }
}
function Put(){

    $requisicao = json_decode(file_get_contents('php://input'), true);
    if(isset($requisicao['nome']) && isset($requisicao['cep']) && isset($requisicao['rua']) && isset($requisicao['complemento']) && isset($requisicao['idEndereco'])){
        $params = [$requisicao['nome'], $requisicao['cep'], $requisicao['rua'], $requisicao['complemento'], $requisicao['idEndereco']];
        $sql = "UPDATE enderecos 
                SET nome = ?, cep = ?, rua = ?, complemento = ?
                WHERE idEndereco = ?";
        $update = Query::Send($sql, $params);
        if ($update[0] === 200){
            return $update;
        }
        else{
            return[400, "Cadastro de endereço falhou: ".$inserir[1]];
        }

    } 
}
function Patch(){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    if (isset($requisicao['idEndereco'])){
        $params = [$requisicao['idEndereco']];
        $sql = "UPDATE enderecos SET isActive = True WHERE idEndereco = ?";
        $resultados = Query::Send($sql,$params);
        if ($resultados[0] === 200){
            return $resultados;
        }
        else{
            return[400, "Alteração de endereço principal falhou: ".$resultados[1]];
        }
    }
    else{
        return [400, "ID do endereço inválido"];
    }
}

switch ($_SERVER['REQUEST_METHOD']){
    case 'POST':
        $resultados = Post();
        break;
    case 'DELETE':
        $resultados = Delete();
        break;
    case 'GET':
        $resultados = Get();
        break;
    case 'PUT':
        $resultados = Put();
        break;
    case 'PATCH':
        $resultados = Patch();
        break;
}
if(isset($resultados)){

    echo Response::Enviar($resultados[0], $resultados[1]);
}
else{
    echo Response::Enviar(405, "Método não suportado");
}

?>