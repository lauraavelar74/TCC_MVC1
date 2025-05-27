<?php
$host = 'localhost';
$user = 'root'; // ou o seu usuário do MySQL
$pass = '';     // sua senha
$db = 'biblioteca_mvc'; // substitua pelo nome real do seu banco

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
