<?php
session_start();
require_once("conexao.php");
if(!isset($_SESSION['admin_logado'])){
    header('Location:login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
</head>
<body>
    <h2>Cadastrar Produto</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <!--Campos do formulário para inserir informações do produto-->
        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" required><p></p>
        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="descricao" required></textarea><p></p>
        <label for="preco">Preço</label>
        <input type="number" name="preco" id="preco" step="0.01" required><p></p>
        <label for="desconto">Desconto</label>
        <input type="number" name="desconto" id="desconto" step="0.01" required><p></p>
        <label for="estoque">Estoque</label>
        <input type="number" name="estoque" id="estoque" required><p></p>
        <label for="categoria_id">Categoria</label>
        <select name="categoria_id" id="categoria_id" required>
            <?php
            foreach($categorias as $categoria)
            {?>
            <option value="<?php echo $categoria['CATEGORIA_ID'];?> "> <?php echo $categoria['CATEGORIA_NOME'];?> </option> <?php } ?>            
        </select><p></p>
        <label for="ativo">Ativo</label>
        <input type="checkbox" name="ativo" id="ativo" value="1" checked><p></p>
        <div id="containerImagens">
            <div class="image-input">
                <input type="text" name="imagem_url[]" placeholder="URL da imagem" required>
                <input type="number" name="imagem_ordem[]" placeholder="Ordem" min="1" required><p></p>
            </div>
        </div>
        <button type="button" onclick="adicionarImagem()">Adicionar mais imagens</button><p></p>
        <button type="submit">Cadastrar produto</button><p></p>
    </form>
    <a href="painel_admin.php">Voltar ao Painel do Administrador</a>
</body>
</html>