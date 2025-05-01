<?php

require_once __DIR__ ."/conexao.php";




class Query{

    public static $conn;                        #Atributo estático pra ficar na desgraça do escopo aqui

    public static function setConn($conexao){   #Set da conexão
        self::$conn = $conexao;                 #Usando self:: ao invés de this-> porque é uma função estática
    }

    public static function startTransaction() {
        #Inicia uma transação
        if (self::$conn->begin_transaction()) {
            return [200, "Transação iniciada com sucesso"];
        }
        return [400, "Erro ao iniciar transação: " . self::$conn->error];
    }

    public static function commit() {
        #Confirma uma transação
        if (self::$conn->commit()) {
            return [200, "Transação confirmada com sucesso"];
        }
        return [400, "Erro ao confirmar transação: " . self::$conn->error];
    }

    public static function rollback() {
        #Desfaz uma transação
        if (self::$conn->rollback()) {
            return [200, "Transação revertida com sucesso"];
        }
        return [400, "Erro ao reverter transação: " . self::$conn->error];
    }
    

    public static function Send($sql, $params = []){

        $statement = self::$conn->prepare($sql);
        if($statement === false){               #Tratamento de erros na preparação
            return[400,"Erro ao preparar o statement:".self::$conn->error]; 
        }
        $tiposStr = '' ;  #Loop para montar a string que vai ser passada como parâmetro na hora do bind
        if(!empty($params)){
            foreach($params as $p){     
                $tipo = gettype($p);
                switch ($tipo){
                    case 'string':
                        $tiposStr .= "s";
                        break;
                    case 'integer':
                        $tiposStr .= "i";
                        break;
                    case 'double':
                        $tiposStr .= "d";
                        break;
                }
            }
            $statement->bind_param($tiposStr, ...$params); #O ... pega os itens dentro do array e passa individualmente como parâmetros, muito mágico :)
        };
        
        $execucao = $statement->execute();

        if($execucao === false){
            return[400,"Erro na execução do statement: ". self::$conn->error];
        }

        if (preg_match('/^INSERT/i', $sql)) { # Verifica se a query inicia com INSERT e busca o id que foi adicionado
            $idInserido = self::$conn->insert_id;
           
        }

        if($statement->affected_rows >= 0){ #Checa se alguma linha foi afetada, indicando que foi uma operação que não gera retorno de array
            if (isset($idInserido)){
                return [200, "Operação bem-sucedida com ".$statement->affected_rows." linhas afetadas", $idInserido];
            }
            return [200, "Operação bem-sucedida com ".$statement->affected_rows." linhas afetadas"];
        }

        $resultado = $statement->get_result();
        $arrayResultados = [];
        
                
        while ($linha = $resultado->fetch_assoc()) {    #Loop que executa até o valor retornado pela função ser null,
                                                        #indicando que todos os resultados foram adicionados ao array
            $arrayResultados[] = $linha;                #Organiza o resultado em um array associativo
        }
                
        if (!empty($arrayResultados)){      #Verifica se o array resultante está vazio e o retorna caso não esteja 
            return [200, $arrayResultados]; #Retorna status e dados
        }
        else{
                    
            return [404,"Nenhum resultado encontrado :c"];
                              
        }
    }    
}

Query::setConn($conexao);

?>