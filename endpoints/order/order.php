<?php

require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";


#$listaProdutos = [['idProduto' => 1, 'quantidade' => 5],['idProduto' => 2, 'quantidade' => 8]]; Modelo de array que deve entrar como parâmetro
function ajustarEstoque($listaProdutos){
    $params = [];
    #Transação que tenta ajustar todos os itens da compra, com a operação não sendo concluida caso um deles falhe, garantindo a integridade do estoque
    $inicio = Query::Send("START TRANSACTION;");
    try{
        foreach($listaProdutos as $produtos){

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

            $sql = "UPDATE produtos
                    SET quantidade = GREATEST(quantidade - ?, 0)
                    WHERE idProduto = ?;";
            $params = [$produtos['quantidade'], $produtos['idProduto']];
            $transacao = Query::Send($sql, $params);
            if($transacao[0] !== 200){
                throw new Exception("Houve um erro ao ajustar o estoque do produto com id".$produtos['idProduto']. ': '.$transacao[1]);
            }
        }

    $commit = Query::Send("COMMIT;");

    if ($commit[0] !== 200){
        throw new Exception("Erro ao confirmar a transação".$commit[1]);
    }
    return [200, "Transação concluída com sucesso"];
    }
    catch (Exception $e){

        $rollback = Query::Send("ROLLBACK;");
        print_r($rollback);
                    #Rollback caso aconteça um erro na transação
        return [400, $e->getMessage()];
    }
}
function Get(){
    if(isset($_GET['id'])){
    $params = [$_GET['id']];
    $resultados = Query::Send("SELECT * FROM PEDIDOS WHERE idPedido = ?", $params);
    return $resultados;
    }
    else{
        Response::Enviar(400, "Requisição inválida");
    }
}

function Post(){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    $listaProdutos = [];
    foreach ($requisicao['listaProdutos'] as $r){
        $listaProdutos[] = ['idProduto'=>$r['idProduto'],'quantidade'=> $r['quantidade']];
    }
    $resultados = ajustarEstoque($listaProdutos);
    return $resultados;
    #$conteudo = Query::Send("   INSERT INTO CONTEUDO (idPedido, idProduto, quantidade, valorCompra) 
                              #  VALUES (?, ?, ?, ?)", $params);

    #$resultados = Query::Send("   INSERT INTO PEDIDOS (idUsuario, valorTotal, valorFinal, valorDesconto, valorFrete, metodoPagamento, enderecoEntrega, dateEHora) VALUES
          #                      (?, ?, ?, ?, ?, ?, ?, ?)", $params);
}

#TEM Q MEXER BASTANTE AINDA
    
    

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        $resultados = Get();
        break;
    case 'POST':
        $resultados = Post();
        break;
    }
echo Response::Enviar(...$resultados);

?>