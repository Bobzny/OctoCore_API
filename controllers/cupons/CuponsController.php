<?php

class CuponsController{
    public static function ValidarCupom($parametros){
        if(isset($parametros[0])){
            $resposta = Query::Send("SELECT * FROM CUPONS WHERE CODIGO = ?",$parametros);

            if ($resposta[0] === 200 && $resposta[1][0]['isActive'] === 1){
                return [200, $resposta[1][0]];
            }
            else{
                return [404, "Cupom inválido ou inativo"];
            }

        }
        else{
            return [400, "Requisição inválida"];
        }
    }

}




?>