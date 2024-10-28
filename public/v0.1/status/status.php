<?php

require_once __DIR__ ."/../../../api_core/response.php";
require_once __DIR__ ."/../../../api_core/config.php";
require_once __DIR__ ."/../../../api_core/conexao.php";
require_once __DIR__ ."/../../../api_core/query.php";



echo Response::geison('200', 'success', json_encode($produtos))

?>
