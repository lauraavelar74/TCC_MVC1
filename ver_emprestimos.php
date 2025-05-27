<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco
$host = 'localhost';
$db = 'biblioteca_mvc';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Lógica para excluir empréstimo
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['excluir_id'])) {
    $id_excluir = intval($_POST['excluir_id']);
    $conn->query("DELETE FROM emprestimos WHERE id = $id_excluir");
    // Redireciona para a mesma página para atualizar a tabela
    header("Location: ver_emprestimos.php");
    exit();
}

// Consulta para listar empréstimos
$sql = "SELECT e.id, a.nome AS nome_aluno, l.nome_livro AS livro, e.data_emprestimo
        FROM emprestimos e
        JOIN alunos a ON e.aluno_id = a.id
        JOIN livros l ON e.livro_id = l.id";

$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ver Empréstimos</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #4CAF50;
            text-decoration: none;
        }
        form {
            margin: 0;
        }
        button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h2>Empréstimos Registrados</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nome do Aluno</th>
        <th>Livro</th>
        <th>Data do Empréstimo</th>
        <th>Ações</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['nome_aluno']) ?></td>
            <td><?= htmlspecialchars($row['livro']) ?></td>
            <td><?= htmlspecialchars($row['data_emprestimo']) ?></td>
            <td>
                <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este empréstimo?');">
                    <input type="hidden" name="excluir_id" value="<?= $row['id'] ?>">
                    <button type="submit">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="registrar_emprestimo.php">← Registrar novo empréstimo</a>

</body>
</html>

