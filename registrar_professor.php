<?php
// Seu código PHP permanece igual
include ('db.php');

$conn = new mysqli("localhost", "root", "", "biblioteca_mvc"); 
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = trim($_POST["cpf"]);
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    if (!empty($cpf) && !empty($nome) && !empty($email) && !empty($_POST["senha"])) {
        $sql_verificar = "SELECT cpf FROM professores WHERE cpf = ?";
        $stmt_verificar = $conn->prepare($sql_verificar);
        $stmt_verificar->bind_param("s", $cpf);
        $stmt_verificar->execute();
        $stmt_verificar->store_result();

        if ($stmt_verificar->num_rows > 0) {
            $mensagem = "Erro: CPF já cadastrado!";
        } else {
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrar Professor</title>
    <style>
        /* Centralização com flexbox */
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center; /* horizontal */
            align-items: center; /* vertical */
            font-family: Arial, sans-serif;
            background: #f0f0f0;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: left;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .mensagem {
            margin-top: 15px;
            color: red;
            font-weight: bold;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrar Professor</h2>
        <?php if (!empty($mensagem)) { echo "<p class='mensagem'>$mensagem</p>"; } ?>

        <form action="registrar_professor.php" method="POST">
            <label for="cpf">CPF:</label>
            <input type="text" maxlength="11" id="cpf" name="cpf" required />

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required />

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required />

            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>