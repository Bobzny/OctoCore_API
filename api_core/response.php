<?php

require_once __DIR__ ."./config.php";

class Response{
    public static function geison($status = 200, $message = 'success', $data = null){
        header('Access-Control-Allow-Origin: *'); // Permite acesso de qualquer origem
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Define métodos permitidos
        header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Define cabeçalhos permitidos
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
        header('Content-Type: application/json');

        if (API_IS_ACTIVE){
             return json_encode([
            'status' => $status,
            'message' => $message,
            'api_version' => API_VERSION,
            'time_response' => time(),
            'datetime_response' => date('d-m-Y H:m:s'),
            'data' => $data
        ], JSON_PRETTY_PRINT);
        }
        else{
             return json_encode([
            'status' => 400,
            'message' => 'api is dead :c',
            'api_version' => API_VERSION,
            'time_response' => time(),
            'datetime_response' => date('d-m-Y H:m:s'),
            'data' => null
            ], JSON_PRETTY_PRINT);
      
        }
    }
}

