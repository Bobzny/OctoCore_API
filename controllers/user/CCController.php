<?php

class CCController{
    private static function getChave() {
        require_once __DIR__ . '/../../config/config.php';
        if (defined('CHAVE_CC')) {
            return CHAVE_CC;
        } else {
            throw new Exception('CHAVE_CC não definida no config.php');
        }
    }

    public static function Cadastrar($requisicao){    #Adicionar um novo cartão
        
            if (isset($requisicao['numeroCC']) && isset($requisicao['cvv']) && isset($requisicao['validade']) && isset($requisicao['idUsuario'])){ #verifica se o JSON possui os campos necessários
                
                $chave = self::getChave();

                $cipher = "AES-128-CTR"; #Algoritmo de criptografia
    
                $original = $requisicao['numeroCC']."::".$requisicao['cvv'];
    
                
                $iv = random_bytes(openssl_cipher_iv_length($cipher));
                $encriptado = openssl_encrypt($original, $cipher, $chave, 0, $iv);
                $dadosCC = base64_encode($encriptado."::".$iv);
    
    
                $dividido = explode("-", $requisicao['numeroCC']);
                $finalCC = end($dividido); #Armazena os 4 últimos números 
                
                $params = [$dadosCC, $requisicao['validade'], $finalCC, $requisicao['idUsuario']];
               
                $resultados = Query::Send("INSERT INTO cartoes (dadosCC, validade, final, idUsuario) VALUES 
                                            (?, ?, ?, ?)", $params); 
                    
                if($resultados[0] == 200){
                    return[200, "Cartão cadastrado com sucesso"];
                }
                else{
                    return[400, "Erro ao cadastrar o cartão"];
                }
                
        
            }
            else{
                return [400,"Requisição inválida"];
            }
            
    
    }
    public static function Buscar($parametros){ # Buscar o final dos cartões do usuário
        if (isset($parametros)){
            $params = [$parametros[0]];
            $resultados = Query::Send("SELECT idCartao, final FROM cartoes WHERE idUsuario = ? ORDER BY isActive DESC",$params);
            return [$resultados[0], $resultados[1]];
    
        }
        return [400, "Requisição inválida"];
    }
    public static function Delete($requisicao){  # Deletar cartão
        if (isset($requisicao['IDCC'])){
            $params = [$requisicao['IDCC']];
            $resultados = Query::Send("DELETE FROM cartoes WHERE idCartao = ?",$params);
            if ($resultados[0] === 200){
                return [200, "Cartão excluído com sucesso"];
            }
            else{
                return[400, "Exclusão de CC falhou: ".$resultados[1]];
            }
        }
        else{
            return [400, "Requisição inválida"];
        }
    }
    public static function Alterar($requisicao){ #Alterar o cartão principal do usuário
        if (isset($requisicao['IDCC']) && isset($requisicao['idUsuario'])){
            $verificarExistencia = Query::Send("SELECT * FROM cartoes WHERE idCartao = ? AND idUsuario = ?", [$requisicao['IDCC'], $requisicao['idUsuario']]);
            if ($verificarExistencia[0] !== 200){
                return [400, "CC ou usuário inválido"];
            }
            $desativar = Query::Send("UPDATE cartoes SET isActive = False WHERE idUsuario = ?", [$requisicao['idUsuario']]);
            if($desativar[0] !== 200){
                return [400, "Erro ao desmarcar outros cartões contate o suporte :)"];
            }
            $params = [$requisicao['IDCC']];
            $sql = "UPDATE cartoes SET isActive = True WHERE idCartao = ?";
            $resultados = Query::Send($sql,$params);
            if ($resultados[0] === 200){
                return [200, "CC principal alterado com sucesso"];
            }
            else{
                return[400, "Alteração de CC principal falhou: ".$resultados[1]];
            }
        }
        else{
            return [400, "ID do CC ou usuário inválido"];
        }
    }
}


?>