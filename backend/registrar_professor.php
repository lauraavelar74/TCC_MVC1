<?php

include ('../db.php');

$conn = new mysqli("localhost", "root", "", "biblioteca_mvc"); // Conexão com o banco de dados
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = trim($_POST["cpf"]);
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT); // Hash da senha para segurança

    if (!empty($cpf) && !empty($nome) && !empty($email) && !empty($_POST["senha"])) {
        // Verifica se o CPF já está cadastrado
        $sql_verificar = "SELECT cpf FROM professores WHERE cpf = ?";
        $stmt_verificar = $conn->prepare($sql_verificar);
        $stmt_verificar->bind_param("s", $cpf);
        $stmt_verificar->execute();
        $stmt_verificar->store_result();

        if ($stmt_verificar->num_rows > 0) {
            $mensagem = "Erro: CPF já cadastrado!";
        } else {
            // Insere um novo professor no banco de dados
            $sql = "INSERT INTO professores (cpf, nome, email, senha) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $cpf, $nome, $email, $senha);

            if ($stmt->execute()) {
                $mensagem = "Professor registrado com sucesso!";
            } else {
                $mensagem = "Erro ao registrar professor.";
            }

            $stmt->close();
        }
        $stmt_verificar->close();
    } else {
        $mensagem = "Todos os campos são obrigatórios!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Professor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            max-width: 400px;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #45a049;
        }
        .mensagem {
            color: red;
            margin-top: 10px;
        }
        a {
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
        }
        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Registrar Professor</h2>
    
    <?php if (!empty($mensagem)) { echo "<p class='mensagem'>$mensagem</p>"; } ?>

    <form action="registrar_professor.php" method="POST">
        <label for="cpf">CPF:</label>
        <input type="text" maxlength="11" id="cpf" name="cpf" required>

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Registrar</button>
    </form>

    <a href="../index.php">Voltar para o painel</a>
</body>
</html>
