<?php
class PictureController{
    private static function ApagarImagemAnterior($idUsuario) {
        $pasta = __DIR__ . '/../../img/users/';
        $extensoes = [
                            'jpg',
                            'jpeg',
                            'png',
                            'gif',
                            'bmp',
                            'webp',
                            'wbmp',
                            'xbm',
                            'tiff',
                            'ico'
                    ];
        foreach ($extensoes as $ext) {
            $arquivo = $pasta . 'profile_' . $idUsuario . '.' . $ext;
            if (file_exists($arquivo)) {
                if(unlink($arquivo)){
                    return [200, "Imagem $arquivo excluída com sucesso"];
                }
                else{
                    return [500, "Erro ao excluir imagem $arquivo"];
                }
            }
        }
        return [404, "Imagem não encontrada"];
    }

    private static function SalvarImagem($imagem, $idUsuario){
        
        $pasta = __DIR__ . '/../../img/users/';
        //Cria a pasta caso não exista
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        //Define o nome do arquivo e o caminho completo
        $nomeArquivo = 'profile_' . $idUsuario . '.' . explode('/', $imagem['type'])[1];
        $caminhoArquivo = $pasta . $nomeArquivo;

        //Move o arquivo temporário para o diretório de uploads
        if(!move_uploaded_file($imagem['tmp_name'], $caminhoArquivo)){
            return [500, "Erro ao mover arquivo para o diretório de uploads"];
        }
        return [200, $nomeArquivo];
        
    }

    public static function AlterarImagemPerfil($requisicao){
        # Alterar imagem de perfil
        if (isset($requisicao['idUsuario']) && isset($_FILES['imagem'])){
            //Verificar se o arquivo é uma imagem
            if (explode('/',$_FILES['imagem']['type'])[0] !== 'image'){
                return [400, "Arquivo enviado deve ser uma imagem"];
            }

            //Verificar se o tamanho do arquivo é maior que 5MB
            if ($_FILES['imagem']['size'] > 5 * 1024 * 1024){
                return [400, "Imagem deve ter no máximo 5MB"];
            }

            //Apaga a imagem anterior, se existir
            $excluirAnterior = self::ApagarImagemAnterior($requisicao['idUsuario']);
            if($excluirAnterior[0] !== 200){
                return [500, "Erro ao excluir imagem anterior"];
            }
            //Executa função auxiliar para salvar a imagem
            $salvar = self::SalvarImagem($_FILES['imagem'], $requisicao['idUsuario']);
            if($salvar[0] !== 200){
                return [500, "Erro ao salvar imagem"];
            }

            //Atualiza o nome do arquivo no banco de dados
            $params = [$salvar[1], $requisicao['idUsuario']];
            $sql = "UPDATE usuarios SET linkPFP = ? WHERE idUsuario = ?";

            $resultados = Query::Send($sql, $params);
            if($resultados[0] === 200){
                return [$resultados[0], [
                    "message" => "Imagem de perfil alterada com sucesso",
                    "linkPFP" => 'http://localhost/OctoCore_API/img/users/' . $salvar[1]
                ]];
            }
            else{
                return [$resultados[0], "Erro na alteração da imagem"];
            }
        }
        # Resetar imagem de perfil pra default
        else if(isset($requisicao['idUsuario']) && $_FILES['imagem'] === null){ 
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