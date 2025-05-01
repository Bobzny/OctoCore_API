<?php
require_once __DIR__ ."/../../core/query.php";
require_once __DIR__ ."/../../core/response.php";



function Post(){    #Adicionar um novo cartão
    $requisicao = json_decode(file_get_contents('php://input'), true); #Atribui na variável qualquer json que tenha sido enviado em uma requisição
    
        if (isset($requisicao['numeroCC']) && isset($requisicao['cvv']) && isset($requisicao['validade']) && isset($requisicao['idUsuario'])){ #verifica se o JSON possui os campos necessários
            
            #Chave de criptografia que será vai pra um .env no futuro 🤡
            $key = "BOBZITO";
            $cipher = "AES-128-CTR"; #Algoritmo de criptografia

            $original = $requisicao['numeroCC']."::".$requisicao['cvv'];

            
            $iv = random_bytes(openssl_cipher_iv_length($cipher));
            $encriptado = openssl_encrypt($original, $cipher, $key, 0, $iv);
            $dadosCC = base64_encode($encriptado."::".$iv);

            // Descriptografar
            #$decrypted = openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
            #echo "Descriptografado: $decrypted\n";





            $dividido = explode("-", $requisicao['numeroCC']);
            $finalCC = end($dividido); #Armazena os 4 últimos números 
            
            $params = [$dadosCC, $requisicao['validade'], $finalCC, $requisicao['idUsuario']];
           
            $resultados = Query::Send("INSERT INTO cartoes (dadosCC, validade, final, idUsuario) VALUES 
                                        (?, ?, ?, ?)", $params); 
                                        
            return $resultados;
    
        }
        else{
            return [400,"Requisição inválida"];
        }
        

}
function Get(){ # Buscar cartões de um usuário
    if (isset($_GET['user'])){
        $params = [$_GET['user']];
        $resultados = Query::Send("SELECT idCartao, final FROM cartoes WHERE idUsuario = ? ORDER BY isActive DESC",$params);
        return [$resultados[0], $resultados[1]];

    }
    return [400, "Usuário inválido"];
}
function Delete(){  # Deletar cartão
    $requisicao = json_decode(file_get_contents('php://input'), true);
    if (isset($requisicao['IDCC'])){
        $params = [$requisicao['IDCC']];
        $resultados = Query::Send("DELETE FROM cartoes WHERE idCartao = ?",$params);
        if ($resultados[0] === 200){
            return $resultados;
        }
        else{
            return[400, "Exclusão de CC falhou: ".$resultados[1]];
        }
    }
    else{
        return [400, "ID do CC inválido"];
    }
}
function Patch(){
    $requisicao = json_decode(file_get_contents('php://input'), true);
    if (isset($requisicao['IDCC']) && isset($requisicao['idUsuario'])){
        $desativar = Query::Send("UPDATE cartoes SET isActive = False WHERE idUsuario = ?", [$requisicao['idUsuario']]);
        if($desativar[0] !== 200){
            return [400, "Erro ao desativar outros endereços contate o suporte :)"];
        }
        $params = [$requisicao['IDCC']];
        $sql = "UPDATE cartoes SET isActive = True WHERE idCartao = ?";
        $resultados = Query::Send($sql,$params);
        if ($resultados[0] === 200){
            return $resultados;
        }
        else{
            return[400, "Alteração de CC principal falhou: ".$resultados[1]];
        }
    }
    else{
        return [400, "ID do CC ou usuário inválido"];
    }
}



switch ($_SERVER['REQUEST_METHOD']){
    case 'POST':
        $resultados = Post();
        break;
    case 'DELETE':
        $resultados = Delete();
        break;
    case 'GET':
        $resultados = Get();
        break;
    case 'PATCH':
        $resultados = Patch();
        break;
}
if(isset($resultados)){

    echo Response::Enviar($resultados[0], $resultados[1]);
}
else{
    echo Response::Enviar(405, "Método não suportado");
}

?>