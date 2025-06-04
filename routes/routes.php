<?php


class Routes{

    public static $rotas = [
        //O primeiro elemento do array é o nome do controller, o segundo é o nome da função que será chamada
        //O terceiro elemento indica se a rota requer autenticação
        "GET" => [  'produtos' => ['ProdutosController', 'Buscar', false],
                    'user/cc' => ['CCController', 'Buscar', true],
                    'user/endereco' => ['EnderecoController', 'Buscar', true],
                    'cupons' => ['CuponsController', 'ValidarCupom', false],
                    'order' => ['OrderController', 'Buscar', true],
                    'user/ticket' => ['TicketController', 'Buscar', true]

        ],
        "POST" =>   [   'user/auth' => ['AuthController','Auth', false],
                        'user/signup' => ['SignupController', 'Signup', false],
                        'user/cc' => ['CCController', 'Cadastrar', true],
                        'user/endereco' => ['EnderecoController', 'Cadastrar', true],
                        'order' => ['OrderController', 'CriarOrdem', true],
                        'user/ticket' => ['TicketController', 'CriarTicket', true],
                        'user/recovery' => ['RecoveryController', 'Recuperar', false]
                    ],
        "PUT" => [
                        'user/endereco' => ['EnderecoController', 'Editar', true]
        ],
        "PATCH" => [
                        'user/cc' => ['CCController', 'Alterar', true],
                        'user/endereco' => ['EnderecoController', 'Alterar', true],
                        'user/picture' => ['PictureController', 'AlterarImagemPerfil', true]
        ],
        "DELETE" => [
                        'user/cc' => ['CCController', 'Delete', true],
                        'user/endereco' => ['EnderecoController', 'Delete', true]
        ]
        
                ];

    public static function getRoute($method, $caminho){
        if (isset(self::$rotas[$method][$caminho])){
            return self::$rotas[$method][$caminho];
        }
        else{
            return false;
        }
    }
}









?>