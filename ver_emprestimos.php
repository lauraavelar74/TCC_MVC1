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
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Ver Empréstimos e Atrasos</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>

<!-- Sidebar igual painel.php -->
<div class="sidebar">
    <h2>Menu</h2>
    <form action="ver_emprestimos.php" method="get">
        <button type="submit">Ver Empréstimos</button>
    </form>
    <form action="registrar_emprestimo.php" method="get">
        <button type="submit">Registrar Empréstimo</button>
    </form>
    <form action="registrar_aluno.php" method="get">
        <button type="submit">Registrar Aluno</button>
    </form>
    <form action="registrar_livro.php" method="get">
        <button type="submit">Registrar Livros</button>
    </form>
    <form action="buscar_livros.php" method="get">
        <button type="submit">Buscar Livros</button>
    </form>
</div>

<!-- Conteúdo principal -->
<div class="main-content">
    <h1>Empréstimos</h1>

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
                        <th class="actions">Ações</th>
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
                        <td class="actions">
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
                        <th class="actions">Ações</th>
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
                        <td class="actions">
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
</div>

</body>
</html>
