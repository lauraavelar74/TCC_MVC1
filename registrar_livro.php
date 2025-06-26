<?php
$msg = '';
$livros = [];

$conn = new mysqli('localhost', 'root', '', 'biblioteca_mvc');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');

    if ($titulo && $autor && $isbn) {
        $stmt = $conn->prepare("INSERT INTO livros (nome_livro, nome_autor, isbn) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $titulo, $autor, $isbn);

        if ($stmt->execute()) {
            $msg = "Livro registrado com sucesso.";
        } else {
            $msg = "Erro ao registrar livro.";
        }
        $stmt->close();
    } else {
        $msg = "Por favor, preencha todos os campos.";
    }

    header("Location: registrar_livro.php?msg=" . urlencode($msg));
    exit;
}

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
        font-family: Arial, sans-serif;
        margin: 0;
        height: 100vh;
        display: flex;
        background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTubqVsiiaI3XveJXybnBzvEmD5e1CnzO49mQ&s');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
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
        font-size: 28px;
        color: #000;
        margin-bottom: 30px;
    }

    .form-box {
        background-color: rgba(255, 240, 245, 0.9);
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(255, 105, 180, 0.3);
        margin-bottom: 40px;
        width: 100%;
        max-width: 500px;
    }

    .form-box label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .form-box input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        margin-bottom: 20px;
        border-radius: 8px;
    }

    .form-box button[type="submit"] {
        background-color: #ff69b4;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        width: 100%;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
    }

    .form-box button[type="submit"]:hover {
        background-color: #e754a3;
    }

    table {
        border-collapse: separate;
        border-spacing: 0 12px;
        background-color: rgba(255, 182, 193, 0.5);
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 5px 8px rgba(255, 182, 193, 0.7);
    }

    th, td {
        padding: 12px 40px;
        text-align: center;
        background-color: rgba(255, 192, 203, 0.9);
        border-radius: 10px;
        box-shadow: 0 3px 5px rgba(255, 105, 180, 0.5);
    }

    .message {
        margin-bottom: 20px;
        padding: 10px 15px;
        border-radius: 8px;
        text-align: center;
        font-weight: bold;
    }

    .success {
        background-color: rgb(9, 189, 51);
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid rgb(255, 0, 25);
    }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Menu</h2>
    <form action="ver_emprestimos.php" method="get"><button type="submit">Ver Empréstimos</button></form>
    <form action="registrar_emprestimo.php" method="get"><button type="submit">Registrar Empréstimo</button></form>
    <form action="registrar_aluno.php" method="get"><button type="submit">Registrar Aluno</button></form>
    <form action="registrar_livro.php" method="get"><button type="submit">Registrar Livros</button></form>
    <form action="buscar_livros.php" method="get"><button type="submit">Buscar Livros</button></form>
</div>

<div class="main-content">
    <h1>Registrar Novo Livro</h1>

    <?php if (isset($_GET['msg'])): ?>
        <div class="message <?= strpos($_GET['msg'], 'sucesso') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="form-box">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" required>

        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" required>

        <button type="submit">Registrar</button>
    </form>

    <?php if (!empty($livros)): ?>
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
        <p style="margin-top: 20px;">Nenhum livro registrado ainda.</p>
    <?php endif; ?>
</div>

</body>
</html>
