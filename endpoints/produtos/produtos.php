<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";


function Get(){
    if (isset($_GET['categoria'])){
        $categoria = array($_GET['categoria']);
        #Comando sql que da join das duas tabelas com base em id de categoria e depois filtra pelo nome especificado na url
        $resultados = Query::Send(" 
        SELECT p.* 
        FROM PRODUTOS p
        INNER JOIN CATEGORIAS c ON p.idCategoria = c.idCategoria
        WHERE c.nome = ?
        ",$categoria);
      
    
    }else if(isset($_GET['busca'])){
        $busca = array($_GET['busca']);
        $resultados = Query::Search("produtos", $busca);
    }
    else{ #Else para pesquisas sem parâmetro
        $resultados = Query::Send("SELECT * FROM Produtos");
  
    }
    return $resultados;
}
function Post(){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    $params = [$requisicao['nome'], $requisicao['valorUnitario'], $requisicao['descricao'],$requisicao['linkImagem'], $requisicao['idCategoria']];
    $resultados = Query::Send("INSERT INTO PRODUTOS (idProduto, nome, valorUnitario, quantidade, descricao, linkImagem, idCategoria) VALUES (null, ?, ?, 10, ?, ?, ?)", $params);     
    return $resultados;
}
function Delete(){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    $params = [$requisicao['idProduto']];
    $resultados = Query::Send('DELETE FROM Produtos WHERE idProduto = ?', $params);
    return $resultados;
}
function Put(){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    $params = [$requisicao['nome'], $requisicao['valorUnitario'], $requisicao['descricao'],$requisicao['linkImagem'], $requisicao['idProduto']];
    $resultados = Query::Send('UPDATE Produtos SET nome = ?, valorUnitario = ?, descricao = ?, linkImagem = ? WHERE idProduto = ?', $params);
    return $resultados;
}

#Switch case para executar o método correto
switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        $resultados = Get();
        break;
    case 'POST':
        $resultados = Post();
        break;
    case 'DELETE':
        $resultados = Delete();
        break;
    case 'PUT':
        $resultados = Put();
        break;
}

echo Response::Enviar(...$resultados);

?>