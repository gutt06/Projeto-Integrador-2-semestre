<?php
//uma sessão é iniciada e verifica se um administrador está logado, se não estiver ele é redirecionado para a página de login
session_start();
if(!isset($_SESSION['admin_logado'])){
    header('Location:login.php');
    exit();
}

//o script faz uma conexão com o banco de dados usando os detalhes de configuração especificados em conexao.php
require_once('conexao.php');

//se a pagina foi acessada via método GET o script tenta recuperar os detalhes do produto com base no ID passado na URL 
if ($_SERVER['REQUEST_METHOD'] == 'GET') { //a superglobal $_server é um array que contém informações sobre cabeçalhos, caminhos e locais de scripts
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("SELECT * FROM PRODUTO
            LEFT JOIN PRODUTO_IMAGEM ON PRODUTO.PRODUTO_ID = PRODUTO_IMAGEM.PRODUTO_ID WHERE PRODUTO.PRODUTO_ID = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        header('Location: listar_produtos.php');
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $imagem_url = $_POST['imagem_url'];
    try {
        // Atualizando a tabela PRODUTO
        $stmt = $pdo->prepare("UPDATE PRODUTO SET PRODUTO_NOME = :nome, PRODUTO_DESC = :descricao, PRODUTO_PRECO = :preco WHERE PRODUTO_ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
        $stmt->execute();

        // Atualizando a tabela PRODUTO_IMAGEM
        $stmt = $pdo->prepare("UPDATE PRODUTO_IMAGEM SET IMAGEM_URL = :imagem_url WHERE PRODUTO_ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':imagem_url', $imagem_url, PDO::PARAM_STR);
        $stmt->execute();

        header('Location: listar_produtos.php');
        exit();
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
</head>
<body>
    <h2>Editar Produto</h2>
    <form action="editar_produto.php" method="post">
        <input type="hidden" name="id" value="<?php echo $produto['PRODUTO_ID'];?>">

        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?php echo $produto['PRODUTO_NOME'];?>"><br>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao"><?php echo $produto['PRODUTO_DESC'];?>"></textarea><br>

        <label for="preco">Preço:</label>
        <input type="number" name="preco" id="preco" value="<?php echo $produto['PRODUTO_PRECO'];?>"><br>

        <label for="imagem_url">URL da Imagem</label>
        <input type="text" name="imagem_url" id="imagem_url" value="<?php echo $produto['IMAGEM_URL'];?>"><br>

        <input type="submit" value="Atualizar Produto">
    </form>
    <a href="listar_produtos.php">Voltar à Lista de Produtos</a>
</body>
</html>