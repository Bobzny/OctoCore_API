<?php
class TicketController{

    public static function CriarTicket($requisicao){
        //Verificando se a requisição contém os parâmetros necessários
        if (isset($requisicao['idUsuario']) && isset($requisicao['idPedido']) && isset($requisicao['titulo']) && isset($requisicao['descricao'])){
            $params = [$requisicao['idUsuario'], $requisicao['idPedido'], $requisicao['titulo'], $requisicao['descricao']];
            $sql = "INSERT INTO tickets (idUsuario, idPedido, titulo, descricao) 
                    VALUES (?, ?, ?, ?)";
    
            $resultados = Query::Send($sql, $params);
            if($resultados[0] === 200){
                return [$resultados[0], "Ticket com ID ".$resultados[2]." criado com sucesso"];
            }
            else{
                return [$resultados[0], "Erro na criação do ticket"];
            }
        }
        else{
            return [400, "Requisição inválida"];
        }
    }
    public static function Buscar($parametros){
        $resultados = Query::Send("SELECT * FROM tickets WHERE idUsuario = ? ",$parametros);
        return $resultados;
    }
}


?>