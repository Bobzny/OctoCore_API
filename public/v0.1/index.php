<?php

require_once __DIR__ ."/../../api_core/response.php";
require_once __DIR__ ."/../../conexao.php";
$resultado = $conexao->query('SELECT * FROM produtos');

echo Response::geison('200', 'success', $resultado)

?>
