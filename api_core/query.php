<?php

include('conexao.php');

class Query{

    public static function Send($sql){

        $resultado = $conexao->query($sql);

        $arrayResultados = [];
    
        while ($row = $resultado->fetch_assoc()) {
            $arrayResultados[] = $row;
        }

        return $arrayResultados;
    }

}    




