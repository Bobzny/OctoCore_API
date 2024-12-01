<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";


function Get(){
    if (isset($_GET['categoria'])){
        $params = [$_GET['categoria']];
        #Comando sql que da join das duas tabelas com base em id de categoria e depois filtra pelo nome especificado na url
        $sql = "SELECT p.* 
                FROM PRODUTOS p
                INNER JOIN CATEGORIAS c ON p.idCategoria = c.idCategoria
                WHERE c.nome = ?";
        #Concatena a busca caso tenha sido passada como parâmetro
        if (isset($_GET['busca'])){
            $sql .= " AND p.nome LIKE ?";
            $busca = '%'.$_GET['busca'].'%';
            $params[] = $busca;
        }
        $resultados = Query::Send($sql,$params);
        
        
      
    
    }
    else{ #Else para pesquisas sem categoria
        $params = [];
        $sql = "SELECT * FROM Produtos";
        if (isset($_GET['busca'])){
            $sql .= " WHERE nome LIKE ?";
            $busca = '%'.$_GET['busca'].'%';
            $params[] = $busca;
        }
        $resultados = Query::Send($sql,$params);
  
    }
    return [$resultados[0], $resultados[1]];
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $resultados = Get();
    echo Response::Enviar($resultados[0], $resultados[1]);
}
else{
    echo Response::Enviar(405, "Método não suportado");
}

?>