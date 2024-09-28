<?php 
$host = 'www.thyagoquintas.com.br';
$db = 'Bravo';
$user = 'bravo';
$pass = 'bravo';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;$charset";
$options = [ 
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //Especifica como o PDO deve lidar com erros. Nesse caso o PDO lança  uma exceção.
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //Define o modo de obtenção de dados padrão nas consultas. Nesse caso, os resultados retornaraõ como arrays associativos.
    PDO::ATTR_EMULATE_PREPARES => false, //Controla se o PDO deve emular prepared statement do lado do cliente ou do lado do servidor. false será do lado servidor.
];
try{
$pdo = new PDO($dsn,$user,$pass,$options);
echo 'Conexão bem sucedida';
} catch (\PDOException $e){
    throw new \PDOException($e->getMessage(),(int)$e->getCode());
}
?>