<?php
require_once __DIR__ ."/../models/query.php";


#Simulação de pagamento usando uma tabela separada no banco de dados
#Uma das implementações futuras é uma integração com uma API de pagamento real

class Payment {


    public static function Decrypt($dadosCC){

        #Chave temporária para simulação de pagamento
        $cipher = "AES-128-CTR";
        $chave = "TEMP_KEY";

        # Descriptografando dados recebidos
        $dadosCC = base64_decode($dadosCC);
        list($encriptado, $iv) = explode("::", $dadosCC); # Divide a string e bota as duas partes nas variáveis de list
        $decriptado = openssl_decrypt($encriptado, $cipher, $chave, 0, $iv);
        list($cc, $cvv) = explode("::", $decriptado);
    
        return [$cc, $cvv];
    }

    public static function Efetuar($dados){

        try{

            
            $params = [$dados[0], $dados[1]];
            $dadosCC = Query::Send("SELECT dadosCC, validade, final FROM cartoes WHERE idCartao = ? AND idUsuario = ?",$params);

         

            $inicio = Query::startTransaction();
     
            if (isset($dadosCC[1][0]['dadosCC'])){

                $decriptado = Payment::Decrypt($dadosCC[1][0]['dadosCC']);
                $validade = $dadosCC[1][0]['validade'];
                $inicio = Query::startTransaction();

                $sqlVerificar = "SELECT limiteDisponivel FROM pagamento WHERE numeroCC = ? AND cvv = ? AND validade = ? FOR UPDATE";
                $params = [...$decriptado, $validade]; #Dados do CC e validade
                $resultado = Query::Send($sqlVerificar, $params);
           

                if($resultado[0] !== 200){
                    throw new Exception("CC inválido");
                }
                $limiteDisponivel = $resultado[1][0]['limiteDisponivel'];

                if ($dados[2] > $limiteDisponivel) {
                    throw new Exception("Limite insuficiente no CC");
                }
                $sqlUpdate = "  UPDATE pagamento
                                SET limiteDisponivel = GREATEST(limiteDisponivel - ?, 0)
                                WHERE numeroCC = ? AND cvv = ? AND validade = ?";
                $params = [$dados[2], ...$decriptado, $validade];
                $update = Query::Send($sqlUpdate, $params);

                if($update[0] !== 200){
                    throw new Exception("Transação falhou");
                }


                $commit = Query::commit();
                return [200, "Transação aprovada", $dadosCC[1][0]['final']];

            }
            else{
                throw new Exception("Solicitação inválida");
            }
    
        }
        catch (Exception $e){
            $rollback = Query::rollback();
            return [400, $e->getMessage()];
        }
        
    }
    
    public static function Estorno($dados){ //Retorna o saldo para a conta caso a transação do pedido falhe
        try{

            
            $params = [$dados[0], $dados[1]];
            $dadosCC = Query::Send("SELECT dadosCC, validade FROM cartoes WHERE idCartao = ? AND idUsuario = ?",$params);

            $inicio = Query::startTransaction();
     
            if (isset($dadosCC[1][0]['dadosCC'])){

                $decriptado = Payment::Decrypt($dadosCC[1][0]['dadosCC']);
                $validade = $dadosCC[1][0]['validade'];
                $inicio = Query::startTransaction();

                
                $sqlUpdate = "  UPDATE pagamento
                                SET limiteDisponivel = GREATEST(limiteDisponivel + ?, 0)
                                WHERE numeroCC = ? AND cvv = ? AND validade = ?";
                $params = [$dados[2], ...$decriptado, $validade];
                $update = Query::Send($sqlUpdate, $params);

                if($update[0] !== 200){
                    throw new Exception("Estorno falhou");
                }


                $commit = Query::commit();
                return [200, "Estorno concluido"];

            }
            else{
                throw new Exception("Solicitação inválida");
            }
    
        }
        catch (Exception $e){
            $rollback = Query::rollback();
            return [400, $e->getMessage()];
        }
        
    }
    }








?>