<?php
$servername = 'localhost';
$dbname = 'biblioteca_mvc';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
