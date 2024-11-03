<?php

require_once __DIR__ ."/conexao.php";




class Query{

    public static $conn;                        #Atributo estático pra ficar na desgraça do escopo aqui

    public static function setConn($conexao){   #Set da conexão
        self::$conn = $conexao;                 #Usando self:: ao invés de this-> porque é uma função estática
    }
    
    public static function Send($sql){

        $resultado = self::$conn->query($sql);

        $arrayResultados = [];

        if (!$resultado) { 
            die("Erro na consulta SQL: " . self::$conn->error); 
        }
    
        while ($row = $resultado->fetch_assoc()) {
            $arrayResultados[] = $row;
        }

        if (!empty($arrayResultados)){ #Verifica se o array resultante está vazio e o retorna caso não esteja 
            return $arrayResultados;
        }
        else{
            return "Nenhum resultado encontrado :c";
            
           
        }
    }

}    

Query::setConn($conexao)

?>
