<?php
//Inicia a sessão de gerenciamento do usuário
session_start();

//Importa a configuração de conexão com o banco de dados
require_once('conexao.php');

//Verifica se o administrador está logado
if(!isset($_SESSION['admin_logado'])){
    header("Location:login.php");
    exit();
}

//Bloco que será executado quando o formulário for submetido
if($_SERVER['REQUEST_METHOD']=='POST'){
    //Pegar os valores do formulário que foram enviados via post
    $nome=$_POST['nome'];
    $email=$_POST['email'];
    $senha=$_POST['senha'];
    $ativo=isset($_POST['ativo'])?1:0;

    try{
        $sql="INSERT INTO ADMINISTRADOR (ADM_NOME,ADM_EMAIL,ADM_SENHA,ADM_ATIVO) VALUES (:nome,:email,:senha,:ativo);";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':nome',$nome,PDO::PARAM_STR); //Vinculando os placeholders com as variáveis usando a opção que confirma se os dados são uma string
        $stmt->bindParam(':email',$email,PDO::PARAM_STR);
        $stmt->bindParam(':senha',$senha,PDO::PARAM_STR);
        $stmt->bindParam(':ativo',$ativo,PDO::PARAM_STR);

        $stmt->execute();

        //Pegar o ID do Administrador inserido
        $adm_id=$pdo->lastInsertId();
        echo "<p style='color:blue'>Administrador Cadastrado com sucesso!! ID: ".$adm_id."</p>";
    }catch(PDOException $e){
        echo "<p style='color:red'>Erro ao cadastrar o Administrador".$e->getMessage()."</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Administrador</title>
</head>
<body>
    <h2>Cadastrar Administrador</h2>
    <form action="" method="post">
        <!--Campos do formulário para inserir informações do admnistrador-->
        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" require>
        <p></p>
        <label for="email">Email</label>
        <textarea name="email" id="email" require></textarea>
        <p></p>
        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" require>
        <p></p>
        <label for="ativo">Ativo:</label>
        <input type="checkbox" name="ativo" id="ativo" value="1" checked>
        <p></p>
        <button type="submit">Cadastrar Administrador</button>
        <p></p>
        <a href="painel_admin.php">Voltar ao Painel do Administrador</a>
    </form>
</body>
</html>