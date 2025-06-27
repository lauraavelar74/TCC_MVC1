<?php
include 'db.php';

// Configurações PDO para tratar erros e formato dos dados
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Consulta para buscar empréstimos com nomes relacionados
$sql = "
    SELECT 
        e.id,
        p.nome AS professor_nome,
        a.nome AS aluno_nome,
        l.nome_livro,
        e.data_emprestimo,
        e.data_devolucao
    FROM emprestimos e
    LEFT JOIN professores p ON e.id_professor = p.id
    LEFT JOIN alunos a ON e.id_aluno = a.id
    LEFT JOIN livros l ON e.id_livro = l.id
    ORDER BY e.data_emprestimo DESC
";

$stmt = $pdo->query($sql);
$emprestimos = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Ver Empréstimos Registrados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles.css" />
    <style>
        /* Pequenas melhorias para a tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f7a1b0;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #ffe4ea;
        }
    </style>
</head>
<body>

<!-- Sidebar idêntico ao registrar_emprestimo.php -->
<div class="sidebar">
    <form action="painel.php" method="get"><button type="submit">🏠 Casa</button></form>
    <h2>Menu</h2>
    <form action="ver_emprestimos.php" method="get"><button type="submit">Ver Empréstimos</button></form>
    <form action="registrar_emprestimo.php" method="get"><button type="submit">Registrar Empréstimo</button></form>
    <form action="registrar_aluno.php" method="get"><button type="submit">Registrar Aluno</button></form>
    <form action="registrar_livro.php" method="get"><button type="submit">Registrar Livros</button></form>
    <form action="buscar_livros.php" method="get"><button type="submit">Buscar Livros</button></form>
    <form action="registrar_professor.php" method="get"><button type="submit">Registrar Professor</button></form>
    <form action="relatorio.php" method="get"><button type="submit">Relatório</button></form>
</div>

<!-- Conteúdo principal -->
<div class="main-content">
    <h1>Empréstimos Registrados</h1>

    <?php if (count($emprestimos) === 0): ?>
        <p>Nenhum empréstimo registrado.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Professor</th>
                    <th>Aluno</th>
                    <th>Livro</th>
                    <th>Data Empréstimo</th>
                    <th>Data Devolução</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emprestimos as $emprestimo): ?>
                    <tr>
                        <td><?= htmlspecialchars($emprestimo['id']) ?></td>
                        <td><?= htmlspecialchars($emprestimo['professor_nome']) ?></td>
                        <td><?= htmlspecialchars($emprestimo['aluno_nome']) ?></td>
                        <td><?= htmlspecialchars($emprestimo['nome_livro']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($emprestimo['data_emprestimo']))) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($emprestimo['data_devolucao']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div style="margin-top: 20px;">
        <a href="painel.php" class="voltar">Voltar ao Painel</a>
    </div>
</div>

</body>
</html>
