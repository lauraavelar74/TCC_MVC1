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
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        height: 100vh;
        display: flex;
        background-color: #ffe4e1; /* rosa claro */
        color: #000;
    }

    .sidebar {
        width: 220px;
        background-color: rgba(255, 182, 193, 0.8);
        padding: 30px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 3px 0 10px rgba(255, 105, 180, 0.6);
    }

    .sidebar h2 {
        font-size: 22px;
        margin-bottom: 30px;
        color: #000;
    }

    .sidebar form {
        width: 100%;
        margin-bottom: 15px;
    }

    .sidebar button {
        width: 100%;
        padding: 10px;
        font-size: 15px;
        background-color: rgba(255, 105, 180, 0.9);
        color: #000;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 3px 6px rgba(255, 105, 180, 0.7);
    }

    .sidebar button:hover {
        background-color: rgba(219, 112, 147, 0.9);
        box-shadow: 0 5px 10px rgba(219, 112, 147, 0.9);
    }

    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
    }

    h1 {
        margin-bottom: 30px;
        font-size: 28px;
        color: #000;
    }

    table {
        border-collapse: separate;
        border-spacing: 0 12px;
        background-color: rgba(255, 182, 193, 0.5);
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 5px 8px rgba(255, 182, 193, 0.7);
    }

    td {
        background-color: rgba(255, 192, 203, 0.9);
        border-radius: 10px;
        padding: 12px 40px;
        text-align: center;
        box-shadow: 0 3px 5px rgba(255, 105, 180, 0.5);
    }

    form.cadastro-aluno {
        background-color: rgba(255, 255, 255, 0.95);
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
        max-width: 400px;
        width: 100%;
        box-sizing: border-box;
    }

    form.cadastro-aluno h1 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
        font-size: 28px;
    }

    form.cadastro-aluno label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
        color: #333;
    }

    form.cadastro-aluno input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
        box-sizing: border-box;
    }

    form.cadastro-aluno button[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: rgb(255, 0, 140);
        border: none;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form.cadastro-aluno button[type="submit"]:hover {
        background-color: rgb(255, 0, 217);
    }

    form.cadastro-aluno a.voltar {
        display: inline-block;
        width: 100%;
        background-color: #ff69b4;
        color: white;
        text-align: center;
        padding: 12px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        margin-top: 10px;
        cursor: pointer;
    }
</style>
</head>
<body>

<div class="sidebar">
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
