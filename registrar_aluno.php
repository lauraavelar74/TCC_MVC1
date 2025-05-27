<?php
// Conexão com o banco de dados
$host = "localhost";
$db = "biblioteca_mvc";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST["nome"] ?? '');
    $serie = $conn->real_escape_string($_POST["serie"] ?? '');
    $email = $conn->real_escape_string($_POST["email"] ?? '');

    $sql = "INSERT INTO alunos (nome, serie, email) VALUES ('$nome', '$serie', '$email')";
    if ($conn->query($sql) === TRUE) {
        echo "Aluno cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Aluno</title>
    <style>
        body{
            background-image: url('./img/bg.jpg');
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        label, input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Cadastrar Aluno</h1>
    <form method="post" action="">
        <label for="nome">Nome do aluno:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="serie">Série:</label>
        <input type="text" id="serie" name="serie" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <button type="submit">Salvar</button>
    </form>
</body>
</html>
