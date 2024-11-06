<?php
require_once __DIR__ ."/../../../api_core/query.php";
require_once __DIR__ ."/../../../api_core/response.php";

$categoria = $_GET['categoria'];
#Comando sql que da join das duas tabelas com base em id de categoria e depois filtra pelo nome especificado na url
$resultados = Query::Send(" 
    SELECT p.* 
    FROM PRODUTOS p
    INNER JOIN CATEGORIAS c ON p.idCategoria = c.idCategoria
    WHERE c.nome = '$categoria'
");

echo Response::geison($resultados);

/*{ JSON de resposta para testes
    "status": 200,
    "message": "success",
    "api_version": "0.2",
    "time_response": 1730897889,
    "datetime_response": "06-11-2024 13:11:09",
    "data": [
        {
            "idProduto": "1",
            "nome": "Raizen",
            "valorUnitario": "2400.99",
            "quantidade": "50",
            "descricao": "Potente",
            "linkImagem": null,
            "idCategoria": "1"
        },
        {
            "idProduto": "2",
            "nome": "i9",
            "valorUnitario": "2500.99",
            "quantidade": "10",
            "descricao": "Potente 2",
            "linkImagem": null,
            "idCategoria": "1"
        }
    ]
}*/









?>