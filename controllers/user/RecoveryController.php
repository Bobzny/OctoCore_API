<?php
class RecoveryController{

    public static function Recuperar($requisicao){
        if(isset($requisicao['email']) && isset($requisicao['user'])){
            $sql = "SELECT * FROM usuarios WHERE email = ? AND usuario = ?";
            $params = [$requisicao['email'], $requisicao['user']];
            $resposta = Query::Send($sql, $params);
            if($resposta[0] === 200){
                return [$resposta[0], "E-mail de recuperação enviado com sucesso :)"];
            }
            else{
                return [$resposta[0], "Conta não encontrada ou inválida"];
            }
        }
        else{
            return[405, "Requisição inválida"];
        } 
    }
}

?>