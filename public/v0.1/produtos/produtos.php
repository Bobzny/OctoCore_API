<?php
require_once __DIR__ ."/../../../api_core/query.php";
require_once __DIR__ ."/../../../api_core/response.php";

$categoria = $_GET['categoria'];

$resultados = Query::Send("SELECT * FROM PRODUTOS WHERE idCategoria = 1");

echo Response::geison($resultados);

print_r($resultados[0])








?>