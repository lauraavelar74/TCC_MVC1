<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão
$host = 'localhost';
$db = 'biblioteca_mvc';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

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
        </select><br><br>

        <label>Professor:</label>
        <select name="professor_id" required>
            <option value="">Selecione um professor</option>
            <?php
            $professores = $conn->query("SELECT id, nome FROM professores");
            while ($p = $professores->fetch_assoc()) {
                echo "<option value='{$p['id']}'>{$p['nome']}</option>";
            }
            ?>
        </select><br><br>

        <label>Aluno:</label>
        <select name="aluno_id" required>
            <option value="">Selecione um aluno</option>
            <?php
            $alunos = $conn->query("SELECT id, nome FROM alunos");
            while ($a = $alunos->fetch_assoc()) {
                echo "<option value='{$a['id']}'>{$a['nome']}</option>";
            }
            ?>
        </select><br><br>

        <label>Data de Empréstimo:</label>
        <input type="date" name="data_emprestimo" value="<?= date('Y-m-d') ?>" required><br><br>

        <label>Data de Devolução:</label>
        <input type="date" name="data_devolucao" required><br><br>

        <button type="submit">Registrar Empréstimo</button>
    </form>

    <br>
    <a href="ver_emprestimos.php">← Ver Empréstimos</a>
</body>
</html>

