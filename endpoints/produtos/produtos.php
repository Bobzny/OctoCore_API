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

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $resultados = Get();
    echo Response::Enviar($resultados[0], $resultados[1]);
}
else{
    echo Response::Enviar(405, "Método não suportado");
}

?>