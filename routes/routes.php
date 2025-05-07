<?php


class Routes{

    public $rotas = [

        "GET" => [],
        "POST" =>   [   'users/auth' => ['AuthController','Auth'],
                        'users/signup' => ['SignupController', 'Signup']
                    ],
        "PUT" => [],
        "PATCH" => [],
        "DELETE" => []
        
    ]

    public static function getRoute($method, $params){
        $caminho = $params[0] . '/' .$params[1];
        $rota = $rotas[$method][$caminho];
        return $rota
    }
}









?>