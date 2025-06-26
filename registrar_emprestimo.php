<?php
include 'db.php';

// Configurações PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Se o formulário foi enviado:
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Coleta os dados enviados
    $professor_id = $_POST['professor_id'] ?? null;
    $aluno_id = $_POST['aluno_id'] ?? null;
    $livro_id = $_POST['livro_id'] ?? null;
    $data_emprestimo = $_POST['data_emprestimo'] ?? null;
    $data_devolucao = $_POST['data_devolucao'] ?? null;

    // Validação simples
    if ($professor_id && $aluno_id && $livro_id && $data_emprestimo && $data_devolucao) {
        try {
            // Prepara e executa o INSERT
            $stmt = $pdo->prepare("INSERT INTO emprestimos (id_professor, id_aluno, id_livro, data_emprestimo, data_devolucao)
            VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$professor_id, $aluno_id, $livro_id, $data_emprestimo, $data_devolucao]);

            $msg = "<p class='success'>Empréstimo registrado com sucesso!</p>";
        } catch (PDOException $e) {
            $msg = "<p class='error'>Erro ao registrar empréstimo: " . $e->getMessage() . "</p>";
        }
    } else {
        $msg = "<p class='error'>Preencha todos os campos!</p>";
    }
}

// Consultas para os selects
function query($pdo, $sql) {
    $result = $pdo->query($sql);
    if (!$result) {
        die("Erro na consulta: " . $pdo->errorInfo()[2]);
    }
    return $result;
}

$professores = query($pdo, "SELECT id, nome FROM professores");
$alunos = query($pdo, "SELECT id, nome FROM alunos");
$livros = query($pdo, "SELECT id, nome_livro FROM livros");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Registrar Empréstimo de Livro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

<div class="sidebar">
    <form action="painel.php" method="get">
        <button type="submit">🏠 Casa</button>
    </form>
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

<div class="main-content">
    <h2>Registrar Empréstimo</h2>

    <?php if (!empty($msg)) echo $msg; ?>

    <form action="registrar_emprestimo.php" method="post" class="form-emprestimo">
        <label for="professor">Professor Responsável:</label>
        <select name="professor_id" id="professor" required>
            <?php while ($row = $professores->fetch()) { ?>
                <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['nome']) ?></option>
            <?php } ?>
        </select>

        <label for="aluno">Aluno:</label>
        <select name="aluno_id" id="aluno" required>
            <?php while ($row = $alunos->fetch()) { ?>
                <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['nome']) ?></option>
            <?php } ?>
        </select>

        <label for="livro">Livro:</label>
        <select name="livro_id" id="livro" required>
            <?php while ($row = $livros->fetch()) { ?>
                <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['nome_livro']) ?></option>
            <?php } ?>
        </select>

        <label for="data_emprestimo">Data de Empréstimo:</label>
        <input type="date" name="data_emprestimo" id="data_emprestimo" required />

        <label for="data_devolucao">Data de Devolução:</label>
        <input type="date" name="data_devolucao" id="data_devolucao" required />

        <div class="btn-group">
            <input type="submit" value="Registrar Empréstimo" />
            <a href="painel.php" class="voltar">Voltar ao Painel</a>
        </div>
    </form>
</div>

</body>
</html>
