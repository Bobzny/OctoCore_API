<?php



class Response{
    public static function geison($data = null, $status = 200, $message = 'success' ){
       
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
        
        else{
            header('Content-Type: application/json');
             return json_encode([
            'status' => $status,
            'message' => $message,
            'time_response' => time(),
            'datetime_response' => date('d-m-Y H:m:s'),
            'data' => $data
        ], JSON_PRETTY_PRINT);
        }
    }
}

