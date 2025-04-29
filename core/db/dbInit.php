<?php
#Código que vai ficar pronto para gerar e preencher o banco de dados
#Vai ser mais usado para testes em outras máquinas
#Se tiver um erro de credenciais edite o .env futuramente

require_once __DIR__ '../config/config.php'

$codigo = file("codigoDB.txt"); #Retorna um array com todas as linhas do arquivo de texto
$sql = implode($codigo); #Junta todas as linhas em uma string

#$host = 'localhost';
#$user = 'root';
#$pw = 'admin'; #Comente essa linha se o servidor não tiver senha como as máquinas da faculdade

$connection = new mysqli(HOST, USER, SENHA);

#Agora separado em uma função pra colocar nos botões
function createDB(){
    $resultado = $connection->multi_query($sql); #Só o query não funciona pra fazer todos esses comandos 💀
    #Uns ifs pra dizer claramente oque ta acontecendo
    if($resultado){
        echo "Banco criado e preenchido com sucesso 🎉";
    }
    else{
        echo "Erro ao criar o banco 💀";
    }
}

function dropDB(){
    $resultado = $connection->query("DROP DATABASE octocore;");
}

?>
<body>
    <button>
</body>

