<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

// Processar envio do formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $livro_id = $_POST['livro_id'] ?? null;
    $professor_id = $_POST['professor_id'] ?? null;
    $aluno_id = $_POST['aluno_id'] ?? null;
    $data_emprestimo = $_POST['data_emprestimo'] ?? date('Y-m-d');
    $data_devolucao = $_POST['data_devolucao'] ?? null;

    if ($livro_id && $professor_id && $aluno_id && $data_devolucao) {
        $stmt = $conn->prepare("INSERT INTO emprestimos (livro_id, professor_id, aluno_id, data_emprestimo, data_devolucao) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $livro_id, $professor_id, $aluno_id, $data_emprestimo, $data_devolucao);

        if ($stmt->execute()) {
            header("Location: ver_emprestimos.php");
            exit();
        } else {
            echo "Erro ao registrar empréstimo: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Todos os campos são obrigatórios.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Empréstimo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFC0CB;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #FF69B4;
            padding-top: 20px;
        }

        form {
            background-color: #FFB6C1;
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #FF69B4;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #FF1493;
        }

        select, input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #FF69B4;
            border-radius: 5px;
        }

        button {
            background-color: #FF69B4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }

        button:hover {
            background-color: #FF1493;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #FF69B4;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
            color: #FF1493;
        }
    </style>
</head>
<body>
    <h2>Registrar Empréstimo</h2>

    <form method="POST">
        <label>Livro:</label>
        <select name="livro_id" required>
            <option value="">Selecione um livro</option>
            <?php
            $livros = $conn->query("SELECT id, nome_livro FROM livros");
            while ($l = $livros->fetch_assoc()) {
                echo "<option value='{$l['id']}'>{$l['nome_livro']}</option>";
            }
            ?>
        </select>

        <label>Professor:</label>
        <select name="professor_id" required>
            <option value="">Selecione um professor</option>
            <?php
            $professores = $conn->query("SELECT id, nome FROM professores");
            while ($p = $professores->fetch_assoc()) {
                echo "<option value='{$p['id']}'>{$p['nome']}</option>";
            }
            ?>
        </select>

        <label>Aluno:</label>
        <select name="aluno_id" required>
            <option value="">Selecione um aluno</option>
            <?php
            $alunos = $conn->query("SELECT id, nome FROM alunos");
            while ($a = $alunos->fetch_assoc()) {
                echo "<option value='{$a['id']}'>{$a['nome']}</option>";
            }
            ?>
        </select>

        <label>Data de Empréstimo:</label>
        <input type="date" name="data_emprestimo" value="<?= date('Y-m-d') ?>" required>

        <label>Data de Devolução:</label>
        <input type="date" name="data_devolucao" required>

        <button type="submit">Registrar Empréstimo</button>
    </form>

    <a href="ver_emprestimos.php">← Ver Empréstimos</a>
</body>
</html>
