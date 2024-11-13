<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";


print_r($data);

if(isset($_POST)){
    $requisicao = file_get_contents('php://input');
    $data = json_decode($requisicao, true);
    $param = [$_POST['nome'], $_POST['valorUnitario'], $_POST['descricao'],$_POST['linkImagem'], $_POST['idCategoria']];
    Query::Send("INSERT INTO PRODUTOS (idProduto, nome, valorUnitario, quantidade, descricao, linkImagem, idCategoria) VALUES (null, ?, ?, 10, ?, ?, ?)", $param);

}

else if (isset($_GET['categoria'])){
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

echo Response::geison($resultados);










?>