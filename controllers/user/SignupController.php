<?php

class SignupController{

    public static function usernameDisponivel($username){

        $disponivel = Query::Send("SELECT * FROM USUARIOS WHERE USUARIO = ?", [$username]);
        if($disponivel[0] == 404){
            return true;
        }
        else{
            return false;
        }
    }
    public static function emailDisponivel($email){

        $disponivel = Query::Send("SELECT * FROM USUARIOS WHERE EMAIL = ?", [$email]);
        if($disponivel[0] == 404){
            return true;
        }
        else{
            return false;
        }
    }
    
    

    public static function Signup($requisicao){

        if (isset($requisicao['user']) && isset($requisicao['email']) && isset($requisicao['password'])){ #verifica se o JSON possui os campos necessários
            
            $hash = password_hash($requisicao['password'], PASSWORD_BCRYPT);
            $params = [$requisicao['user'], $requisicao['email'], $hash];

            if (!self::usernameDisponivel($requisicao['user'])){
                return[400, "Nome de usuário indisponível"];
            }
            if (!self::emailDisponivel($requisicao['email'])){
                return[400, "Email indisponível"];
            }


            
            $resultados = Query::Send("INSERT INTO usuarios (usuario, email, senha) VALUES (?, ?, ?)", $params);
            if($resultados[0] == 200){
                return[200, "Cadastro realizado com sucesso"];
            }
            else{
                return[400,"Falha no cadastro, consulte o suporte"]; 
            }  
    
        }
        else{
            return[400,"Requisição inválida"];
        }
    }
}
    


?>