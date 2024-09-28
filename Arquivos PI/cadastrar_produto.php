<?php
session_start();

require_once("conexao.php");
if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}
//________________________________________________________________________
//bloco de consulta para categoria 
try{
    $stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
    $stmt_categoria-> execute();
    $categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    echo "<p style='color:red;'>Erro ao buscar categorias: . $e->getMessage(). </p>";
}
//________________________________________________________________________

//bloco que será executado quando o formulário for submetido
if($_SERVER['REQUEST_METHOD']=='POST'){
    //Pegamos os valores do post enviados via formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $desconto = $_POST['desconto'];
    $estoque = $_POST['estoque'];
    $categoria_id = $_POST['categoria_id'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $imagem_urls= $_POST['imagem_url'];
    $imagem_ordens= $_POST['imagem_ordem'];
//________________________________________________________________________

//bloco para inserir no banco de dados, os dados capturados do formulário PRODUTOS

try{
    $sql = "INSERT INTO PRODUTO (PRODUTO_NOME, PRODUTO_DESC, PRODUTO_PRECO, CATEGORIA_ID, PRODUTO_ATIVO, PRODUTO_DESCONTO) VALUES (:nome, :descricao, :preco, :categoria_id, :ativo, :desconto);";
    $stmt =$pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR); //Vicula placeholder com a variavel, PARAM_STR serve para seleionar apenas o que é string, deixando mais seguro 
    $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
    $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
    $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
    $stmt->bindParam(':desconto', $desconto, PDO::PARAM_STR);

    $stmt->execute();
    
    //Pegar o ID do último produto inserido no banco de dados 
    $produto_id = $pdo->lastInsertId();

//________________________________________________________________________

//bloco para inserir no banco de dados, os dados capturados do formulário PRODUTO_ESTOQUE

    $sql_estoque = "INSERT INTO PRODUTO_ESTOQUE (PRODUTO_ID, PRODUTO_QTD) VALUES (:produto_id, :estoque);";
    $stmt_estoque =$pdo->prepare($sql_estoque);

    $stmt_estoque->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
    $stmt_estoque->bindParam(':estoque', $estoque, PDO::PARAM_INT);
 

    $stmt_estoque->execute();
    
//________________________________________________________________________

//bloco para inserir no banco de dados, os dados capturados do formulário PRODUTO_IMAGEM

    foreach($imagens_urls as $index=> $url){
        $ordem = $imagem_ordens[$index];

    $sql_imagem = "INSERT INTO PRODUTO_IMAGEM (IMAGEM_URL, PRODUTO_ID, IMAGEM_ORDEM) VALUES (:url_imagem, :produto_id, :imagem_ordem);";
    $stmt_imagem =$pdo->prepare($sql_estoque);

    $stmt_imagem->bindParam(':url_imagem', $url, PDO::PARAM_STR);
    $stmt_imagem->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
    $stmt_imagem->bindParam(':imagem_ordem', $ordem, PDO::PARAM_INT);


    $stmt_imagem->execute();
    }
    echo "<p style='color:green;'>Produto Cadastrado com sucesso</p>";
    }catch(PDOException $e){
        echo "<p style='color:red;'>Erro ao cadastrar o Produto". $e->getMessage() . "</p>";
    }

}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <script>
        // Adiciona um novo campo de imagem URL e ordem
        function adicionarImagem() {
            // Cria uma variável e joga nela o elemento identificado por id='containerImagens', que é uma div que conterá os divs de inputs
            const containerImagens = document.getElementById('containerImagens');
            // Criar uma nova div e jogar na variável novoDiv. Esse novo div tem a classe 'imagem-input'
            const novoDiv = document.createElement('div');
            novoDiv.className = 'imagem-input';

            // Cria um elemento de input e joga na variável novoInputURL
            const novoInputURL = document.createElement('input');
            novoInputURL.type = 'text';
            novoInputURL.name = 'imagem_url[]'; // Corrigido para 'imagem_url[]'
            novoInputURL.placeholder = 'URL da imagem';
            novoInputURL.required = true;

            // Cria um elemento de input e joga na variável novoInputOrdem
            const novoInputOrdem = document.createElement('input');
            // Define atributos desse input criado
            novoInputOrdem.type = 'number';
            novoInputOrdem.name = 'imagem_ordem[]'; // Corrigido para 'imagem_ordem[]'
            novoInputOrdem.placeholder = 'Ordem';
            novoInputOrdem.min = '1';
            novoInputOrdem.required = true;

            // Incorpora esses dois inputs criados na div definida como novoDiv
            novoDiv.appendChild(novoInputURL);
            novoDiv.appendChild(novoInputOrdem);

            // Incorpora a div novoDiv na div mais externa, denominada containerImagens
            containerImagens.appendChild(novoDiv);
        }
    </script>
</head>
<body>
    <h2>Cadastrar Produto</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <!-- Campos do formulário para inserir informações do Produto -->
        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" required>
        <p></p>

        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="descricao" required></textarea>
        <p></p>

        <label for="preco">Preço</label>
        <input type="number" name="preco" id="preco" step="0.01" required>
        <p></p>

        <label for="desconto">Desconto</label>
        <input type="number" name="desconto" id="desconto" step="0.01" required>

        <label for="estoque">Estoque</label>
        <input type="number" name="estoque" id="estoque" required>

        <label for="categoria_id">Categoria</label>
        <select name="categoria_id" id="categoria_id" required>
            <?php
            foreach ($categorias as $categoria) { ?>
                <option value="<?php echo $categoria['CATEGORIA_ID']; ?>"> <?php echo $categoria['CATEGORIA_NOME']; ?> </option>
            <?php } ?>
        </select>
        <p></p>

        <label for="ativo">Ativo</label>
        <input type="checkbox" name="ativo" id="ativo" value="1" checked>
        <p></p>

        <div id="containerImagens">
            <div class="imagem-input">
                <input type="text" name="imagem_url[]" placeholder="URL da imagem" required>
                <input type="number" name="imagem_ordem[]" placeholder="Ordem" min="1" required>
            </div>
        </div>
        <button type="button" onclick="adicionarImagem()">Adicionar mais Imagens</button>
        <p></p>
        <button type="submit">Cadastrar Produto</button>
    </form>
    <a href="painel_admin.php">Voltar ao Painel do Administrador</a>

</body>
</html>
