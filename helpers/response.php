<?php


//Retorna um json com o codigo http e dados definidos
//retorna null caso nenhum dado seja passado, mas precisa que o código http seja passado
class Response{
    public static function json($status, $data = null){
       
        #Resposta para preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { //Serve para retornar um 200 nas preflight do navegador
            http_response_code(200);
            exit();
        }
        
        else{
            header('Content-Type: application/json');
            http_response_code($status);                //Define o código http como o que foi passado pelo controller
             return json_encode([
            'time_response' => time(),                  //Retorna o timecode de quando a resposta foi gerada
            'datetime_response' => date('d-m-Y H:m:s'), //Retorna a data completa da requisição
            'data' => $data                             //Dados recebidos pelo controller
        ], JSON_PRETTY_PRINT);                          //Deixa o Json organizado quando acessado
        }
    }
}

