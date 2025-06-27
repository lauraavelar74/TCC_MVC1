<?php
// ver_livros.php

$host = "localhost";
$db   = "biblioteca_mvc";
$user = "root";
$pass = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $stmt = $pdo->query("SELECT id, nome_livro, nome_autor, isbn FROM livros ORDER BY nome_livro ASC");
    $livros = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Erro na conexão ou consulta: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Ver Livros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Link pro CSS que seu registrar_emprestimo.php usa -->
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

<!-- Sidebar igual do registrar_emprestimo.php -->
<div class="sidebar">
    <h2>Menu</h2>
    <form action="painel.php" method="get"><button type="submit">🏠 Casa</button></form>
    <form action="ver_emprestimos.php" method="get"><button type="submit">Ver Empréstimos</button></form>
    <form action="registrar_emprestimo.php" method="get"><button type="submit">Registrar Empréstimo</button></form>
    <form action="registrar_aluno.php" method="get"><button type="submit">Registrar Aluno</button></form>
    <form action="ver_livros.php" method="get"><button type="submit">Ver Livros</button></form>
    <form action="buscar_livros.php" method="get"><button type="submit">Buscar Livros</button></form>
    <form action="registrar_professor.php" method="get"><button type="submit">Registrar Professor</button></form>
    <form action="relatorio.php" method="get"><button type="submit">Relatório</button></form>
</div>

<!-- Conteúdo principal -->
<div class="main-content">
    <h1>Livros Registrados</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome do Livro</th>
                <th>Autor</th>
                <th>ISBN</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($livros) > 0): ?>
                <?php foreach ($livros as $livro): ?>
                    <tr>
                        <td><?= htmlspecialchars($livro['id']) ?></td>
                        <td><?= htmlspecialchars($livro['nome_livro']) ?></td>
                        <td><?= htmlspecialchars($livro['nome_autor']) ?></td>
                        <td><?= htmlspecialchars($livro['isbn']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;">Nenhum livro encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
