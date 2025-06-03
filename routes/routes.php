<?php


class Routes{

    public static $rotas = [

        "GET" => [],
        "POST" =>   [   'users/auth' => ['AuthController','Auth'],
                        'users/signup' => ['SignupController', 'Signup']
                    ],
        "PUT" => [],
        "PATCH" => [],
        "DELETE" => []
        
                ];

    public static function getRoute($method, $params){
        $caminho = $params[0] . '/' .$params[1];
        $rota = self :: $rotas[$method][$caminho] ?? null; // deixei o array de caminhos estaticos e coloquei o self para referencia o array de si mesmo sem precisar instaciar.
        return $rota;
    }
}









?>