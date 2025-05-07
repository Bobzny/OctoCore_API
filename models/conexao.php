<?php

require_once __DIR__ .'/../config/config.php';

//Todas as credenciais foram movidas para o config.php !!!!

$conexao = new mysqli(HOST, USER, SENHA, DB_NAME);

?>