<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";




if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    $params = [$requisicao['nome'], $requisicao['valorUnitario'],$requisicao['dataNasc'], $requisicao['corPrincipal'], $requisicao['descricao'],$requisicao['linkImagem'], $requisicao['idCategoria']];
    $resultados = Query::Send("INSERT INTO PRODUTOS (nome, valorUnitario, dataNasc, corPrincipal,  descricao, linkImagem, idCategoria) VALUES (?, ?, ?, ?, ?,?,?)", $params);

}
else if($_SERVER['REQUEST_METHOD'] === 'GET'){
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
}
else if ($_SERVER['REQUEST_METHOD'] === 'DELETE'){
    
    $requisicao = json_decode(file_get_contents('php://input'), true);
    $params = [$requisicao['idProduto']];
    $resultados = Query::Send('DELETE FROM Produtos WHERE idProduto = ?', $params);

}
else if ($_SERVER['REQUEST_METHOD'] === 'PUT'){

    $requisicao = json_decode(file_get_contents('php://input'), true);
    $params = [$requisicao['nome'], $requisicao['valorUnitario'],$requisicao['dataNasc'],$requisicao['corPrincipal'], $requisicao['descricao'],$requisicao['linkImagem'], $requisicao['idProduto']];
    $resultados = Query::Send('UPDATE Produtos SET nome = ?, valorUnitario = ?, dataNasc = ?, corPrincipal = ?, descricao = ?, linkImagem = ? WHERE idProduto = ?', $params);
    echo Response::geison($resultados[1]);
    die;

}
echo Response::geison($resultados);










?>