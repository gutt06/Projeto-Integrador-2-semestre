<?php
$nome = $_POST['nome']; //o nome da variavel pode ser qualquer uma
$email = $_POST['email']; //existe o metodo post e get

// O get mostra na URL e o post não mostra

echo "Nome: " . $nome . "<br>";
echo "Email: " . $email . "<br>";
?>