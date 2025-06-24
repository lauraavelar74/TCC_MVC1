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
    header("Location: ver_emprestimos.php");
    exit();
}

// Consulta para listar todos os empréstimos
$sqlTodos = "SELECT e.id, a.nome AS nome_aluno, p.nome AS nome_professor, l.nome_livro AS livro, 
             e.data_emprestimo, e.data_devolucao
             FROM emprestimos e
             JOIN alunos a ON e.id_aluno = a.id
             JOIN professores p ON e.id_professor = p.id
             JOIN livros l ON e.id_livro = l.id
             ORDER BY e.data_emprestimo DESC";

$resultTodos = $conn->query($sqlTodos);
if (!$resultTodos) {
    die("Erro na consulta de todos os empréstimos: " . $conn->error);
}

// Consulta para listar empréstimos atrasados:
// data_devolucao IS NULL e data_emprestimo + 7 dias < hoje
$sqlAtrasados = "SELECT e.id, a.nome AS nome_aluno, p.nome AS nome_professor, l.nome_livro AS livro, 
                 e.data_emprestimo, e.data_devolucao
                 FROM emprestimos e
                 JOIN alunos a ON e.id_aluno = a.id
                 JOIN professores p ON e.id_professor = p.id
                 JOIN livros l ON e.id_livro = l.id
                 WHERE e.data_devolucao IS NULL 
                   AND DATE_ADD(e.data_emprestimo, INTERVAL 7 DAY) < CURDATE()
                 ORDER BY e.data_emprestimo ASC";

$resultAtrasados = $conn->query($sqlAtrasados);
if (!$resultAtrasados) {
    die("Erro na consulta de empréstimos atrasados: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Ver Empréstimos e Atrasos</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 40px;
        background-color: #FFC0CB;
    }

    .flex-container {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
    }

    .container {
        background-color: #FFB6C1;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 15px #FF69B4;
        max-width: 600px;
        flex: 1 1 500px;
        min-width: 300px;
    }

    h2 {
        color: #FF69B4;
        margin-bottom: 20px;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
    }

    th, td {
        padding: 12px 10px;
        text-align: center;
        border-bottom: 1px solid #FF69B4;
        color: #4a0033;
        font-size: 14px;
    }

    th {
        background-color: #FF69B4;
        color: white;
        font-size: 16px;
    }

    tr:nth-child(even) {
        background-color: #FFC0CB;
    }

    tr:hover {
        background-color: #ffe0ec;
    }

    form {
        margin: 0;
    }

    button {
        background-color: #FF69B4;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
        font-size: 14px;
    }

    button:hover {
        background-color: #FF1493;
    }

    a {
        display: block;
        margin-top: 15px;
        color: #FF69B4;
        text-decoration: none;
        font-weight: bold;
        text-align: center;
        font-size: 16px;
    }

    a:hover {
        text-decoration: underline;
        color: #FF1493;
    }
</style>
</head>
<body>

<div class="flex-container">
    <div class="container">
        <h2>Todos os Empréstimos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Aluno</th>
                    <th>Professor</th>
                    <th>Livro</th>
                    <th>Empréstimo</th>
                    <th>Devolução</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $resultTodos->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['nome_aluno']) ?></td>
                    <td><?= htmlspecialchars($row['nome_professor']) ?></td>
                    <td><?= htmlspecialchars($row['livro']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['data_emprestimo']))) ?></td>
                    <td>
                        <?= 
                            $row['data_devolucao'] 
                            ? htmlspecialchars(date('d/m/Y', strtotime($row['data_devolucao']))) 
                            : "<em>Não devolvido</em>" 
                        ?>
                    </td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Excluir este empréstimo?');">
                            <input type="hidden" name="excluir_id" value="<?= $row['id'] ?>">
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <a href="registrar_emprestimo.php">← Registrar novo empréstimo</a>
    </div>

    <div class="container">
        <h2>Empréstimos Atrasados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Aluno</th>
                    <th>Professor</th>
                    <th>Livro</th>
                    <th>Empréstimo</th>
                    <th>Atraso (dias)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $resultAtrasados->fetch_assoc()):
                $dataEmprestimo = strtotime($row['data_emprestimo']);
                $diasAtraso = floor((time() - ($dataEmprestimo + 7*86400)) / 86400);
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['nome_aluno']) ?></td>
                    <td><?= htmlspecialchars($row['nome_professor']) ?></td>
                    <td><?= htmlspecialchars($row['livro']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', $dataEmprestimo)) ?></td>
                    <td><?= $diasAtraso ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Excluir este empréstimo?');">
                            <input type="hidden" name="excluir_id" value="<?= $row['id'] ?>">
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
