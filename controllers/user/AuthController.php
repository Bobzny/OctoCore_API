<?php

class AuthController{

    public static function Auth($requisicao){
    
        if (isset($requisicao['email']) && isset($requisicao['password'])){ #verifica se o JSON possui os campos necessários
                
            $baseUrl = 'http://localhost/OctoCore_API/img/users/';
            $params = [$requisicao['email']];  
            $resultados = Query::Send("SELECT * FROM USUARIOS WHERE EMAIL = ?",$params); 

            if ($resultados[1][0]['linkPFP'] == 'http://localhost/OctoCore_API/img/default/user.png'){
                $linkImg = 'http://localhost/OctoCore_API/img/default/user.png';
            }
            else{
                $linkImg = $baseUrl . $resultados[1][0]['linkPFP'];
            }
                
            if (is_array($resultados[1])){
                if (password_verify($requisicao['password'], $resultados[1][0]['senha'])){
                    $data = [       "token" => Jwt::Gerar($resultados[1][0]['idUsuario']),
                                    "linkPFP" => $linkImg,
                                    "usuario" => $resultados[1][0]['usuario'],
                                    "email" => $resultados[1][0]['email']
                            ];
                        return[200,$data];
                    }
                    else{
                        return[401,"Credenciais inválidas"];
                    }
    
                }
                else{
                    return[404,"Conta não encontrada"];
                }
                
    
            }
        else{
            return[400,"Requisição inválida"];
        }                                                                                      
    }
}


?>