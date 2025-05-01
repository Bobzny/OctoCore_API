<?php



class Response{
    public static function Enviar($status, $data = null){
       
        #Resposta para preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
        
        else{
            header('Content-Type: application/json');
            http_response_code($status);
             return json_encode([
            'time_response' => time(),
            'datetime_response' => date('d-m-Y H:m:s'),
            'data' => $data
        ], JSON_PRETTY_PRINT);
        }
    }
}

