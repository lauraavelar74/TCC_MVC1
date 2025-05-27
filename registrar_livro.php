<?php
$msg = '';

// Configuração do banco de dados
$host = 'localhost'; // Ex: localhost ou 127.0.0.1
$usuario = 'root'; // Substitua pelo seu usuário do MySQL
$senha = '';     // Substitua pela sua senha do MySQL
$banco = 'biblioteca_mvc'; // Substitua pelo nome do seu banco de dados

// Conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$titulo = '';
$autor = '';
$isbn = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $autor = $_POST['autor'] ?? '';
    $isbn = $_POST['isbn'] ?? '';

    if ($titulo && $autor && $isbn) {
        $stmt = $conn->prepare("INSERT INTO livros (nome_livro, nome_autor, isbn) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $titulo, $autor, $isbn);

        if ($stmt->execute()) {
            $msg = "Livro registrado com sucesso!";
            $titulo = $autor = $isbn = ''; // Limpa os campos após sucesso
        } else {
            $msg = "Erro ao registrar livro: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $msg = "Por favor, preencha todos os campos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar Livro</title>
    <style>
        body {
            background-color: #FFE4E1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        form {
            background-color: #FFF;
            padding: 30px;
            border-radius: 70px;
            box-shadow: 0 0 100px rgba(0, 70, 0, 0.1);
            min-width: 300px;
        }

        h1 {
            text-align: center;
            color: #F08080;
        }

        label {
            display: block;
            margin-top: 30px;
            color: #FFB6C1;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 3px solid #ccc;
            border-radius: 50px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 20px;
            background-color: #F08080;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 15px;
            cursor: pointer;
        }

        button:hover {
            background-color: #FFB6C1;
        }

        p {
            text-align: center;
            color: #F08080;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div>
        <h1>Registrar Livro</h1>

        <?php if ($msg): ?>
            <p><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="titulo">Título do Livro</label>
            <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($titulo) ?>" required>

            <label for="autor">Autor do Livro</label>
            <input type="text" id="autor" name="autor" value="<?= htmlspecialchars($autor) ?>" required>

            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($isbn) ?>" required>

            <button type="submit" name="registrar">Registrar Livro</button>
        </form>
    </div>
</body>
</html>
