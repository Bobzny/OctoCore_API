<?php
class PictureController{
    public static function AlterarImagemPerfil($requisicao){
        # Alterar imagem de perfil
        if (isset($requisicao['idUsuario']) && isset($requisicao['linkPFP'])){ 
            $params = [$requisicao['linkPFP'], $requisicao['idUsuario']];
            $sql = "UPDATE usuarios SET linkPFP = ? WHERE idUsuario = ?";

            $resultados = Query::Send($sql, $params);
            if($resultados[0] === 200){
                return [$resultados[0], "Imagem de perfil alterada com sucesso"];
            }
            else{
                return [$resultados[0], "Erro na alteração da imagem"];
            }
        }
        # Resetar imagem de perfil pra default
        else if(isset($requisicao['idUsuario']) && $requisicao['linkPFP'] === null){ 
            $params = [$requisicao['idUsuario']];
            $sql = "UPDATE usuarios SET linkPFP = DEFAULT WHERE idUsuario = ?";

            $resultados = Query::Send($sql, $params);
            if($resultados[0] === 200){
                return [$resultados[0], "Imagem de perfil removida com sucesso"];
            }
            else{
                return [$resultados[0], "Erro na remoção da imagem"];
            }
        }
        else{   
            return [400, "Requisição inválida"];
        }
    }
}


?>