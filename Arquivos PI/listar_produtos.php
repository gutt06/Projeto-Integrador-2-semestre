<?php
session_start();
require_once('conexao.php');
if(!isset($_SESSION['admin_logado'])){
    header("Location:login.php");
    exit();
}
try{
    $stmt=$pdo->prepare("SELECT PRODUTO.*,CATEGORIA.CATEGORIA_NOME,PRODUTO_IMAGEM.IMAGEM_URL,PRODUTO_ESTOQUE.PRODUTO_QTD
    FROM PRODUTO JOIN CATEGORIA ON PRODUTO.CATEGORIA_ID=CATEGORIA.CATEGORIA_ID
    LEFT JOIN PRODUTO_IMAGEM ON PRODUTO.PRODUTO_ID=PRODUTO_IMAGEM.PRODUTO_ID
    LEFT JOIN PRODUTO_ESTOQUE ON PRODUTO.PRODUTO_ID=PRODUTO_ESTOQUE.PRODUTO_ID");
    $stmt->execute();//Executa a consulta
    $produtos=$stmt->fetchAll(PDO::FETCH_ASSOC);//Transforma os dados que coletamos acima em um array associativo ([chave-valor] chave será os nomes das colunas e os valores serão os dados cadastrados)
    //echo"<pre>";
    //print_r($produtos);
    //echo"</pre>";
}catch(PDOException $e){
    //Em caso de erro na consulta exibe uma mensagem
    echo"<p style='color:red;'>Erro ao listar produtos:".$e-getMessage()."</p>";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Produtos</title>
    <style>
        table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 1rem 1rem;
    font-family: arial;
    }
  
  td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }
  
  tr:nth-child(even) {
    background-color: pink;
    }
    </style>
</head>
<body>
    <h2>Produtos Cadastrados</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Categoria</th>
            <th>Ativo</th>
            <th>Desconto</th>
            <th>Estoque</th>
            <th>Imagem</th>
            <th>Ações</th>
        </tr>
        <?php foreach($produtos as $produto): ?>
            <tr>
                <td><?php echo $produto['PRODUTO_ID'];?></td>
                <td><?php echo $produto['PRODUTO_NOME'];?></td>
                <td><?php echo $produto['PRODUTO_DESC'];?></td>
                <td><?php echo $produto['PRODUTO_PRECO'];?></td>
                <td><?php echo $produto['CATEGORIA_NOME'];?></td>
                <td><?php echo $produto['PRODUTO_ATIVO'];?></td>
                <td><?php echo $produto['PRODUTO_DESCONTO'];?></td>
                <td><?php echo $produto['PRODUTO_QTD'];?></td>
                <td><img src="<?php echo $produto['IMAGEM_URL'];?>" alt="<?php echo $produto['PRODUTO_NOME'];?>" width="50"></td>
                <td>
                    <a href="editar_produto.php?id=<?php echo $produto['PRODUTO_ID'];?>" class="action-btn">Editar</a>
                    <a href="excluir_produto.php?id=<?php echo $produto['PRODUTO_ID'];?>" class="action-btn delete btn">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
    </table> <p></p>
    <a href="painel_admin.php">Voltar ao Painel do Administrador</a>
</body>
</html>