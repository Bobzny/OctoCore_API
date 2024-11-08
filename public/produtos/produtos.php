<?php
require_once __DIR__ ."/../../api_core/query.php";
require_once __DIR__ ."/../../api_core/response.php";

$categoria = $_GET['categoria'];
#Comando sql que da join das duas tabelas com base em id de categoria e depois filtra pelo nome especificado na url
$resultados = Query::Send(" 
    SELECT p.* 
    FROM PRODUTOS p
    INNER JOIN CATEGORIAS c ON p.idCategoria = c.idCategoria
    WHERE c.nome = '$categoria'
");

echo Response::geison($resultados);

/*{JSON para testes
    "status": 200,
    "message": "success",
    "api_version": "0.2",
    "time_response": 1730990040,
    "datetime_response": "07-11-2024 15:11:00",
    "data": [
        {
            "idProduto": "1",
            "nome": "Raizen",
            "valorUnitario": "2400.99",
            "quantidade": "50",
            "descricao": "Potente",
            "linkImagem": "https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000147box1.jpg",
            "idCategoria": "1"
        },
        {
            "idProduto": "2",
            "nome": "i9",
            "valorUnitario": "2500.99",
            "quantidade": "10",
            "descricao": "Potente 2",
            "linkImagem": "https://static.rbstore.net/1410324639/7834a459-dfe8-5b72-ac03-2a6d98b0f6db.webp",
            "idCategoria": "1"
        },
        {
            "idProduto": "3",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "4",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "5",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "6",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "7",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "8",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "9",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "10",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "11",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "12",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "13",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "14",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        },
        {
            "idProduto": "15",
            "nome": "Teste",
            "valorUnitario": "1200.00",
            "quantidade": "20",
            "descricao": "Cara bom",
            "linkImagem": "https://static.wikia.nocookie.net/paper-shin-aka-keroro-gunsou/images/c/c1/John_Xina.png/revision/latest?cb=20220116222109",
            "idCategoria": "1"
        }
    ]
}*/









?>