<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";

class SignupController{
    

    public static function Signup($requisicao){

        if (isset($requisicao['user']) && isset($requisicao['email']) && $requisicao['password']){ #verifica se o JSON possui os campos necessários
            
            $hash = password_hash($requisicao['password'], PASSWORD_BCRYPT);
            $params = [$requisicao['user'], $requisicao['email'], $hash];
            
            $resultados = Query::Send("INSERT INTO usuarios (usuario, email, senha) VALUES 
                                        (?, ?, ?)", $params);  
            return[$resultados[0],$resultados[1]]; 
    
        }
        else{

            // aqui era pra ser o metodo json? (Estava "Enviar"); 
            echo Response::json(400,"Requisição inválida");
        }
    }
}
    


?>