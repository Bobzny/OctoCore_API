<?php

class ProdutosController{

    public static function Buscar($parametros = []){
        if (isset($parametros[0])){
            #Comando sql que da join das duas tabelas com base em id de categoria e depois filtra pelo nome especificado na url
            $sql = "SELECT p.* 
                    FROM PRODUTOS p
                    INNER JOIN CATEGORIAS c ON p.idCategoria = c.idCategoria
                    WHERE c.nome = ?";     
        }
        else{ #Else para pesquisas sem categoria
            $sql = "SELECT * FROM Produtos";
        }
        $resultados = Query::Send($sql,$parametros);
        return [$resultados[0], $resultados[1]];
    }
    

}


?>