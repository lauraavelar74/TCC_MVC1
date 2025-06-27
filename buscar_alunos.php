<?php
include 'db.php';

$termo = $_GET['q'] ?? '';

if ($termo) {
    $stmt = $pdo->prepare("SELECT id, nome FROM alunos WHERE nome LIKE ? LIMIT 10");
    $stmt->execute(["%$termo%"]);
    $alunos = $stmt->fetchAll();

    foreach ($alunos as $aluno) {
        echo "<div class='sugestao-item' data-id='{$aluno['id']}' data-nome='" . htmlspecialchars($aluno['nome']) . "'>"
            . htmlspecialchars($aluno['nome']) . "</div>";
    }
}
?>
