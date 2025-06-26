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
        echo "<script>alert('Aluno cadastrado com sucesso!');</script>";
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Cadastrar Aluno</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>

<div class="sidebar">
    <form action="painel.php" method="get">
        <button type="submit">🏠 Casa</button>
    </form>
    <h2>Menu</h2>
    <form action="ver_emprestimos.php" method="get">
        <button type="submit">Ver Empréstimos</button>
    </form>
    <form action="registrar_emprestimo.php" method="get">
        <button type="submit">Registrar Empréstimo</button>
    </form>
    <form action="registrar_aluno.php" method="get">
        <button type="submit">Registrar Aluno</button>
    </form>
    <form action="registrar_livro.php" method="get">
        <button type="submit">Registrar Livros</button>
    </form>
    <form action="buscar_livros.php" method="get">
        <button type="submit">Buscar Livros</button>
    </form>
</div>

<div class="main-content">
    <form class="cadastro-aluno" method="post" action="">
        <h1>Cadastrar Aluno</h1>

        <label for="nome">Nome do aluno:</label>
        <input type="text" id="nome" name="nome" required />

        <label for="serie">Série:</label>
        <input type="text" id="serie" name="serie" required />

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required />

        <button type="submit">Salvar</button>
        <a class="voltar" href="painel.php">Voltar</a>
    </form>
</div>

</body>
</html>
