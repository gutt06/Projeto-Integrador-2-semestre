<?php
session_start();

if(isset($_SESSION['mensagem_erro'])){
    echo "<p class='error-message'>" . $_SESSION['mensagem_erro']."</p>";
    unset($_SESSION['mensagem_erro']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="processa_login.php" method="post"> <!--action manda o que for feito neste arquivo para outro, nos caso vai para o arquivo processa_login.php pelo metodo post-->
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required><br>
    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required><br>
    <input type="submit" value="Enviar">
    </form>
</body>
</html>
