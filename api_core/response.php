<?php

require_once __DIR__ ."./config.php";

class Response{
    public static function geison($status = 200, $message = 'success', $data = null){
        header('Content-Type: application/json');
        if (!API_IS_ACTIVE){
            return json_encode([
            'status' => 400,
            'message' => 'api is dead :c',
            'api_version' => API_VERSION,
            'time_response' => time(),
            'datetime_response' => date('d-m-Y H:m:s'),
            'data' => null
            ], JSON_PRETTY_PRINT);
        }
        else{
        return json_encode([
            'status' => $status,
            'message' => $message,
            'api_version' => API_VERSION,
            'time_response' => time(),
            'datetime_response' => date('d-m-Y H:m:s'),
            'data' => $data
        ], JSON_PRETTY_PRINT);
        }
    }
}