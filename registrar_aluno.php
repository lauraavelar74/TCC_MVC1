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
    <meta charset="UTF-8" />
    <title>Cadastrar Aluno</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url('./img/bg.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center; /* horizontal */
            align-items: center; /* vertical */
        }

        form {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <h1>Cadastrar Aluno</h1>

        <label for="nome">Nome do aluno:</label>
        <input type="text" id="nome" name="nome" required />

        <label for="serie">Série:</label>
        <input type="text" id="serie" name="serie" required />

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required />

        <div style="display: flex; gap: 10px;">
    <button type="submit" style="flex: 1; background-color: #4CAF50;">Salvar</button>
    <a href="painel.php" style="
        flex: 1;
        background-color: #ff69b4;
        color: white;
        text-align: center;
        padding: 12px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        display: inline-block;
    ">Voltar</a>
</div>
    </form>
</body>
</html>
