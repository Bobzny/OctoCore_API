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

        if ($resultado === true) {  #Verifica se o mysql retornou apenas verdadeiro, significando que não era uma operação de consulta
            return "Operação bem sucedida";

        }else if ($resultado === false){ #Verifica se houve um erro na exercução do comando e retorna o erro
            die("Erro na consulta SQL: " . self::$conn->error); 

        }else{ #Caso não caia nas duas anteriores significa que foi uma consulta e foi bem sucedida
                
                while ($row = $resultado->fetch_assoc()) { 
                    $arrayResultados[] = $row; #Organiza o resultado em um array associativo
                }
                
                if (!empty($arrayResultados)){ #Verifica se o array resultante está vazio e o retorna caso não esteja 
                    return $arrayResultados;
                }
                else{
                    
                    return "Nenhum resultado encontrado :c";
                    
                   
                }
        }

       
    }

}    

Query::setConn($conexao)

?>
