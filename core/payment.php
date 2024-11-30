<?php
require_once __DIR__ ."/query.php";


#Simulação de pagamento
#Uma das implementações futuras é uma integração com uma API de pagamento

class Payment {

    public static function Efetuar(){
        $aprovacao = rand(0, 1);
        if ($aprovacao === 1){
            return [200, "Transação aprovada"];
        }
        else{
            return [400, "Transação não aprovada"];
        }
        #$inicio = Query::startTransaction();
        #$sqlVerificar = "SELECT limiteDisponivel FROM pagamento WHERE numeroCC = ? AND cvv = ? AND validade = ? FOR UPDATE";
        #$

    }



}





?>