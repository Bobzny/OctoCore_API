<?php

class EnderecoController{
   
    public static function Cadastrar($requisicao){
        if(isset($requisicao['nome']) && isset($requisicao['cep']) && isset($requisicao['rua']) && isset($requisicao['complemento']) && isset($requisicao['idUsuario'])){
            $params = [$requisicao['nome'], $requisicao['cep'], $requisicao['rua'], $requisicao['complemento'], $requisicao['idUsuario']];
            $sql = "INSERT INTO enderecos (nome, cep, rua, complemento, idUsuario) VALUES (?, ?, ?, ?, ?)";
            $resultados = Query::Send($sql, $params);
            if ($resultados[0] === 200){
                return [$resultados[0], "Endereço cadastrado com sucesso"];
            }
            else{
                return[400, "Cadastro de endereço falhou"];
            }

        }
        else{
            return [400, "Requisição inválida"];
        }

    }
    public static function Buscar($parametros){
        if ($parametros[0]){
            $sql = "SELECT * FROM enderecos WHERE idUsuario = ? ORDER BY isActive DESC";
            $resultados = Query::Send($sql, $parametros);
            return $resultados;
        }
        else{
            return [400, "Requisição inválida"];
        }
        
    }
    public static function Delete($requisicao){
        if (isset($requisicao['idEndereco'])){
            $params = [$requisicao['idEndereco']];
            $sql = "DELETE FROM enderecos WHERE idEndereco = ?";
            $resultados = Query::Send($sql,$params);
            if ($resultados[0] === 200){
                return [$resultados[0], "Endereço excluído com sucesso"];
            }
            else{
                return[400, "Exclusão de endereço falhou"];
            }
        }
        else{
            return [400, "ID do endereço inválido"];
        }
    }
    public static function Editar($requisicao){
        if(isset($requisicao['nome']) && isset($requisicao['cep']) && isset($requisicao['rua']) && isset($requisicao['complemento']) && isset($requisicao['idEndereco'])){
            $params = [$requisicao['nome'], $requisicao['cep'], $requisicao['rua'], $requisicao['complemento'], $requisicao['idEndereco']];
            $sql = "UPDATE enderecos 
                    SET nome = ?, cep = ?, rua = ?, complemento = ?
                    WHERE idEndereco = ?";
            $resultados = Query::Send($sql, $params);
            if ($resultados[0] === 200){
                return [$resultados[0], "Endereço atualizado com sucesso"];
            }
            else{
                return[400, "Cadastro de endereço falhou"];
            }

        } 
    }
    public static function Alterar($requisicao){
        if (isset($requisicao['idEndereco']) && isset($requisicao['idUsuario'])){
            $desativar = Query::Send("UPDATE enderecos SET isActive = False WHERE idUsuario = ?", [$requisicao['idUsuario']]);
            if($desativar[0] !== 200){
                return [400, "Erro ao desativar outros endereços contate o suporte :)"];
            }
            $params = [$requisicao['idEndereco']];
            $sql = "UPDATE enderecos SET isActive = True WHERE idEndereco = ?";
            $resultados = Query::Send($sql,$params);
            if ($resultados[0] === 200){
                return [$resultados[0], "Endereço principal alterado com sucesso"];
            }
            else{
                return[400, "Alteração de endereço principal falhou: ".$resultados[1]];
            }
        }
        else{
            return [400, "ID do endereço inválido"];
        }
    }

}
?>