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
$sql = "SELECT e.id, a.nome AS nome_aluno, p.nome AS nome_professor, l.nome_livro AS livro, e.data_emprestimo
        FROM emprestimos e
        JOIN alunos a ON e.aluno_id = a.id
        JOIN professores p ON e.professor_id = p.id
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
    margin: 0;
    padding: 40px 0;
    background-color: #FFC0CB;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h2 {
    text-align: center;
    color: #FF69B4;
    margin-bottom: 30px;
}

table {
    width: 90%;
    max-width: 900px;
    border-collapse: collapse;
    background-color: #FFB6C1;
    box-shadow: 0 0 15px #FF69B4;
    border-radius: 12px;
    overflow: hidden;
}

th, td {
    padding: 14px 16px;
    text-align: center;
    border-bottom: 1px solid #FF69B4;
    color: #4a0033;
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

a {
    display: block;
    text-align: center;
    margin-top: 30px;
    color: #FF69B4;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
    color: #FF1493;
}

form {
    margin: 20px auto;
    width: 90%;
    max-width: 600px;
}

button {
    background-color: #FF69B4;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    margin: 5px auto;
    display: block;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #FF1493;
}

    </style>
</head>
<body>

<h2>Empréstimos Registrados</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nome do Aluno</th>
        <th>Professor</th>
        <th>Livro</th>
        <th>Data do Empréstimo</th>
        <th>Ações</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['nome_aluno']) ?></td>
            <td><?= htmlspecialchars($row['nome_professor']) ?></td>
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

