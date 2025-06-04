<?php
class OrderController{
    //Essa função inteira serve para garantir que o estoque será ajustado sempre corretamente, com transações, rollbacks e checagem de quantidades para garantir isso
    private static function gerenciarEstoque($listaProdutos){
        $inicio = Query::startTransaction();
        try{
            foreach($listaProdutos as $produtos){ #Checagem de estoque
                #FOR UPDATE bloqueio a escrita no campo até que a transação seja finalizada, garatindo que os valores da consulta não mudarão
                $sqlVerificar = "SELECT quantidade FROM produtos WHERE idProduto = ? FOR UPDATE";
                $params = [$produtos['idProduto']];
                $resultado = Query::Send($sqlVerificar, $params);
    
                if($resultado[0] !== 200 || empty($resultado[1])){
                    throw new Exception("Erro ao verificar estoque do produto com ID " . $produtos['idProduto']);
                }
                $estoqueAtual = $resultado[1][0]['quantidade'];
    
                if ($produtos['quantidade'] > $estoqueAtual) {
                    throw new Exception("Estoque insuficiente do produto com ID " . $produtos['idProduto']);
                }
            }
            foreach($listaProdutos as $produtos){ #Ajuste no estoque
    
                $sql = "UPDATE produtos
                        SET quantidade = GREATEST(quantidade - ?, 0)
                        WHERE idProduto = ?;";
                $params = [$produtos['quantidade'], $produtos['idProduto']];
                $transacao = Query::Send($sql, $params);
                if($transacao[0] !== 200){
                    throw new Exception("Houve um erro ao ajustar o estoque do produto com id".$produtos['idProduto']. ': '.$transacao[1]);
                }
            
            }
    
            #Caso não haja nenhuma exceção da commit nas alterações
            $commit = Query::commit();
        
        
            if ($commit[0] !== 200){
                throw new Exception("Erro ao confirmar a transação".$commit[1]);
            }
            return [200, "Transação concluída com sucesso"];
        }
        catch (Exception $e){
    
            #Rollback caso aconteça um erro na transação
            $rollback = Query::rollback();
            return [400, $e->getMessage()];
        }
    }
    private static function valorTotal($listaProdutos){
        try{
            $valorTotal = 0.0;
            foreach($listaProdutos as $produto){
                $params = [$produto['idProduto']];
                $resultados = Query::Send("SELECT valorUnitario FROM produtos WHERE idProduto = ?",$params);
    
                #Validação de resultados
                if (empty($resultados[1]) || !isset($resultados[1][0]['valorUnitario'])) {
                    throw new Exception("Erro ao buscar o valor unitário do produto com ID " . $produto['idProduto']);
                }
                #Somatória de valores parciais de todos os itens desse ID
                $valorParcial = $resultados[1][0]['valorUnitario'] * $produto['quantidade'];
                $valorTotal += $valorParcial;
                #Array com valores para que seja registrado o valor que foi pago por cada item
                $precos[] = ['idProduto' => $produto['idProduto'], 'valorCompra' => $resultados[1][0]['valorUnitario'], 'quantidade' => $produto['quantidade']];
            }
        
            #Retorno do valor total após o foreach ser concluíodo
            return [200, $valorTotal, $precos];
        }
        catch (Exception $e){
            return [400, $e->getMessage()];
        }
        
    }
    private static function valorDesconto($valorTotal, $cupom){
        $params = [$cupom];
        $resposta = Query::Send("SELECT * FROM CUPONS WHERE CODIGO = ?",$params);
        if ($resposta[0] === 200 && $resposta[1][0]['isActive']){
            $valorDesconto = $valorTotal * ($resposta[1][0]['desconto']/100);
            return [200, $valorDesconto];
        }
        else{
            return [400, "Erro ao calcular desconto"];
        }
    }
    public static function CriarOrdem($requisicao){
        try{
            #Cálculo de valor total do pedido direto do banco de dados, evitando inconsistências
            $valorTotal = self::valorTotal($requisicao['listaProdutos']);
            if ($valorTotal[0] !== 200){
                throw new Exception("Erro no cálculo de total".$valorTotal[1]);
            }
    
            if ($requisicao['metodoPagamento'] === 'Pix' || $requisicao['metodoPagamento'] === 'Boleto'){
                $metodoPagamento = $requisicao['metodoPagamento'];
                $estado = 'Em processamento'; 
            }
            else {
                $idCC = (int) $requisicao['metodoPagamento'];
                if ($idCC !== 0){
                    
                    $dados = [$idCC, $requisicao['idUsuario'], $valorTotal[1]];
                    $pagamento = Payment::Efetuar($dados);
                    if($pagamento[0] !== 200){
                        throw new Exception("Erro no pagamento: ".$pagamento[1]);
                    }
                    $estado = "Pagamento aprovado";
                    $metodoPagamento = $pagamento[2];
                }
                else{
                    throw new Exception("Método de pagamento inválido");
                }
                }
            #Ajuste de estoque com os itens do pedido
            $ajuste = self::gerenciarEstoque($requisicao['listaProdutos']); #Fazer alteração no estoque
            if ($ajuste[0] !== 200){
                if ($idCC){
                $estorno = Payment::Estorno($dados); #Estorno do valor caso a compra não seja concluida
                }
                throw new Exception("Erro: ".$ajuste[1]);
            }
            if (isset($requisicao['cupom'])){
                $valorDesconto = self::valorDesconto($valorTotal[1], $requisicao['cupom']);
                if ($valorDesconto[0] !== 200){
                    if ($idCC){
                        $estorno = Payment::Estorno($dados); #Estorno do valor caso a compra não seja concluida
                        }
                    throw new Exception("Erro no cálculo de desconto".$valorTotal[1]);
                }
                
            }
            else{
                $valorDesconto = [200, 0]; # Define o valor de desconto como zero caso não haja nenhum cupom
            }
    
            $valorFinal = $valorTotal[1] - $valorDesconto[1] + $requisicao['valorFrete'];
    
            $params = [$requisicao['idUsuario'], $estado, $valorTotal[1], $valorFinal, $valorDesconto[1], $requisicao['valorFrete'],$metodoPagamento, $requisicao['enderecoEntrega']];
            $ordem = Query::Send("INSERT INTO PEDIDOS (idUsuario, estado, valorTotal, valorFinal, valorDesconto, valorFrete, metodoPagamento, enderecoEntrega, dataEHora) VALUES 
                                    (?, ?, ?, ?, ?, ?, ?,?, NOW())",$params);
    
            if ($ordem[0] !== 200){
                if ($idCC){
                    $estorno = Payment::Estorno($dados); #Estorno do valor caso a compra não seja concluida
                    }
                throw new Exception("Erro na criação da ordem".$ordem[1]);
            }
    
            foreach($valorTotal[2] as $produto){ # Percorre lista de produtos para registrar o conteudo do pedido
    
                $params = [$ordem[2], $produto['idProduto'], $produto['quantidade'], $produto['valorCompra']];
                $conteudo = Query::Send("INSERT INTO CONTEUDO (idPedido, idProduto, quantidade, valorCompra) 
                                            VALUES (?, ?, ?, ?)", $params);
                if ($conteudo[0] !== 200){
                    if ($idCC){
                        $estorno = Payment::Estorno($dados); #Estorno do valor caso a compra não seja concluida
                        }
                    throw new Exception("Erro no registro de conteúdo".$conteudo[1]);
                } 
            }
    
            return [$ordem[0], "Pedido criado com sucesso" . $ordem[1]];
        }
        catch (Exception $e){
            return [400, $e->getMessage()];
        }
    }
    public static function Buscar($parametros){
        if(isset($parametros[1])){
            $resultados = Query::Send("SELECT * FROM PEDIDOS WHERE idPedido = ? AND idUsuario = ?", $parametros);
            return $resultados;
        }
        else if(isset($parametros[0])){
            $resultados = Query::Send("SELECT * FROM PEDIDOS WHERE idUsuario = ?", $parametros);
            return $resultados;
        }
        else{
            return [400, "Requisição inválida"];
        }
    }
    
}

?>