<?php

include('conexao.php');

$resultado = $conexao->query('SELECT * FROM produtos');

$produtos = [];

while ($row = $resultado->fetch_assoc()) {
    $produtos[] = $row;
}



