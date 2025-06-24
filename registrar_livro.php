<?php
$msg = '';
$livros = [];

// Conexão com banco
$conn = new mysqli('localhost', 'root', '', 'biblioteca_mvc');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Registro de livro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // seus dados do formulário
    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');

    if ($titulo && $autor && $isbn) {
        // verificar se já existe
        // salvar no banco
        // definir $msg = mensagem de sucesso ou erro
    } else {
        $msg = "Por favor, preencha todos os campos.";
    }

    // redireciona para buscar_livro.php passando a mensagem
    header("Location: buscar_livro.php?msg=" . urlencode($msg));
    exit;
}



// Buscar todos os livros
$result = $conn->query("SELECT id, nome_livro, nome_autor, isbn FROM livros ORDER BY id DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $livros[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Registrar Livro</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #ffe6f0;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        min-height: 100vh;
        align-items: flex-start;
        padding-top: 40px;
        color: #4a1030;
    }

    .container {
        background: #fff0f5;
        border: 1px solid #f9c5d5;
        border-radius: 12px;
        width: 100%;
        max-width: 480px;
        padding: 30px 40px;
        box-shadow: 0 4px 12px rgba(255, 192, 203, 0.3);
    }

    h1 {
        text-align: center;
        color: #d63384;
        margin-bottom: 30px;
        font-weight: 700;
        font-size: 2rem;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 6px;
        font-weight: 600;
        color: #c71585;
        font-size: 1rem;
    }

    input[type="text"] {
        padding: 12px 14px;
        border: 1.5px solid #f9c5d5;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus {
        border-color: #d63384;
        outline: none;
        box-shadow: 0 0 6px rgba(214, 51, 132, 0.5);
    }

    button {
        background-color: #ff69b4;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #e754a3;
    }

    .message {
        text-align: center;
        margin-bottom: 25px;
        padding: 10px 15px;
        font-weight: 600;
        border-radius: 8px;
        user-select: none;
    }

    .message.success {
        background-color: #d1f7dc;
        color: #2e7d32;
        border: 1.5px solid #4caf50;
    }

    .message.error {
        background-color: #f8d7da;
        color: #842029;
        border: 1.5px solid #dc3545;
    }

    h2 {
        margin-top: 45px;
        color: #a6336b;
        font-weight: 700;
        text-align: center;
    }

    table {
        margin-top: 20px;
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(198, 99, 143, 0.15);
    }

    th, td {
        padding: 14px 12px;
        border-bottom: 1px solid #f9c5d5;
        text-align: left;
        font-size: 1rem;
    }

    th {
        background-color: #f9c5d5;
        color: #4a1030;
        font-weight: 700;
    }

    tr:last-child td {
        border-bottom: none;
    }

    @media (max-width: 520px) {
        .container {
            padding: 20px;
            max-width: 95%;
        }

        input[type="text"], button {
            font-size: 1rem;
        }

        th, td {
            font-size: 0.9rem;
            padding: 10px 8px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h1>Registrar Livro</h1>

    <?php if ($msg): ?>
        <div class="message <?= strpos($msg, 'sucesso') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="autor">Autor</label>
        <input type="text" id="autor" name="autor" required>

        <label for="isbn">ISBN</label>
        <input type="text" id="isbn" name="isbn" required>

        <button type="submit">Registrar Livro</button>
    </form>

    <?php if (!empty($livros)): ?>
        <h2>Livros Registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livros as $livro): ?>
                    <tr>
                        <td><?= $livro['id'] ?></td>
                        <td><?= htmlspecialchars($livro['nome_livro']) ?></td>
                        <td><?= htmlspecialchars($livro['nome_autor']) ?></td>
                        <td><?= htmlspecialchars($livro['isbn']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="margin-top: 40px; font-weight: 600;">Nenhum livro registrado ainda.</p>
    <?php endif; ?>
</div>

</body>
</html>
