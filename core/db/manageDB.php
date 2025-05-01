<?php
#Código que vai ficar pronto para gerar e preencher o banco de dados
#Vai ser mais usado para testes em outras máquinas
#Se tiver um erro de credenciais edite o config

require_once __DIR__ .'/../config/config.php';


#Todas as credenciais foram movidas para o config.php
$connection = new mysqli(HOST, USER, SENHA);

#Agora as ações básicas do banco foram separadas em funções para usar em outra página de forma mais prática em testes
function createDB($conn){
    $codigo = file("codigoDB.txt"); #Retorna um array com todas as linhas do arquivo de texto
    $sql = implode($codigo);        #Junta todas as linhas em uma string

    #Uns ifs pra dizer claramente oque ta acontecendo
    if($resultado = $conn->multi_query($sql)){
        echo "Banco criado e preenchido com sucesso 🎉";
    }
    else{
        echo "Erro ao criar o banco 💀";
    }
}

function deleteDB($conn){
    $resultado = $conn->query("DROP DATABASE octocore;");
    if($resultado){
        echo "Banco morreu💀";
    }
    else{
        echo "Sobreviveu";
    }
}
function resetDB($conn){
    deleteDB($conn);
    createDB($conn);
}


switch ($_GET['option']){
    case 'create':
        createDB($connection);
        break;
    case 'reset':
        resetDB($connection);
        break;
    case 'delete':
        deleteDB($connection);
        break;
}

// if(isset($_GET['create'])){
//     createDB($connection);
// }
// else if(isset($_GET['delete'])){
//     deleteDB($connection);
// }
// elseif (isset($_GET['reset'])) {
//     resetDB($connection);
// }



?>


