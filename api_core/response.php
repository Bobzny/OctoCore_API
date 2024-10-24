<?php

class Response{
    public static function geison($status = 200, $message = 'success', $data = null){
        header('Content-Type: application/json');
        return json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }
}