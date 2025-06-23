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

php
Copy code
<?php
// Dados para conexão com o banco de dados
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "nome_do_banco";

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para verificar e retornar o resultado da consulta ou o erro
function query($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        die("Erro na consulta: " . $conn->error);
    }
    return $result;
}

// Busca os dados dos professores, alunos e livros
$professores = query($conn, "SELECT id, nome FROM professores");
$alunos = query($conn, "SELECT id, nome FROM alunos");
$livros = query($conn, "SELECT id, titulo FROM livros");

// Fecha a conexão após buscar os dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Empréstimo de Livro</title>
</head>
<body>

<h2>Registrar Empréstimo</h2>

<form action="registrar_emprestimo.php" method="post">
    <label for="professor">Professor Responsável:</label>
    <select name="professor_id" id="professor" required>
        <?php while ($row = $professores->fetch_assoc()) { ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
        <?php } ?>
    </select><br><br>

    <label for="aluno">Aluno:</label>
    <select name="aluno_id" id="aluno" required>
        <?php while ($row = $alunos->fetch_assoc()) { ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
        <?php } ?>
    </select><br><br>

    <label for="livro">Livro:</label>
    <select name="livro_id" id="livro" required>
        <?php while ($row = $livros->fetch_assoc()) { ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['titulo']; ?></option>
        <?php } ?>
    </select><br><br>

    <!-- Campos para datas de empréstimo e devolução -->
    <label for="data_emprestimo">Data de Empréstimo:</label>
    <input type="date" name="data_emprestimo" id="data_emprestimo" required><br><br>

    <label for="data_devolucao">Data de Devolução:</label>
    <input type="date" name="data_devolucao" id="data_devolucao" required><br><br>

    <!-- Botão para submeter o formulário -->
    <input type="submit" value="Registrar Empréstimo">
</form>

</body>
</html>