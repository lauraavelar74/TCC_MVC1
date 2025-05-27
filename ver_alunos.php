<?php
// Conexão com o banco de dados
$host = "localhost";
$db = "escola";
$user = "root";
$pass = ""; // altere para sua senha, se houver

$conn = new mysqli($host, $user, $pass, $db);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Processa o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST["nome"]);

    $sql = "INSERT INTO alunos (nome) VALUES ('$nome')";
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
</head>
<body>
    <h1>Cadastrar Aluno</h1>
    <form method="post" action="">
        <label for="nome">Nome do aluno:</label>
        <input type="text" id="nome" name="nome" required>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>
