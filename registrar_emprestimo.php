<?php
include 'db.php';

// Configurações PDO para tratar erros e formato dos dados
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$msg = ''; // Mensagem para feedback ao usuário

// Se o formulário foi enviado via POST:
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Coleta dados do formulário, usa null se não existir
    $professor_id = $_POST['professor_id'] ?? null;
    $aluno_id = $_POST['aluno_id'] ?? null;
    $livro_id = $_POST['livro_id'] ?? null;
    $data_emprestimo = $_POST['data_emprestimo'] ?? null;
    $data_devolucao = $_POST['data_devolucao'] ?? null;

    // Verifica se todos os campos foram preenchidos
    if ($professor_id && $aluno_id && $livro_id && $data_emprestimo && $data_devolucao) {
        try {
            // Insere o empréstimo no banco de dados
            $stmt = $pdo->prepare("INSERT INTO emprestimos (id_professor, id_aluno, id_livro, data_emprestimo, data_devolucao)
                VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$professor_id, $aluno_id, $livro_id, $data_emprestimo, $data_devolucao]);

            $msg = "<p class='success'>Empréstimo registrado com sucesso!</p>";
        } catch (PDOException $e) {
            $msg = "<p class='error'>Erro ao registrar empréstimo: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        $msg = "<p class='error'>Preencha todos os campos!</p>";
    }
}

// Função para facilitar consulta ao banco
function query($pdo, $sql) {
    $result = $pdo->query($sql);
    if (!$result) {
        die("Erro na consulta: " . $pdo->errorInfo()[2]);
    }
    return $result;
}

// Busca dados para popular os selects
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
    <!-- Usa o mesmo CSS do buscar_livro.php -->
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

<!-- Sidebar idêntico ao buscar_livro.php -->
<div class="sidebar">
    <h2>Menu</h2>
    <form action="painel.php" method="get"><button type="submit">🏠 Casa</button></form>
    <form action="ver_emprestimos.php" method="get"><button type="submit">Ver Empréstimos</button></form>
    <form action="registrar_emprestimo.php" method="get"><button type="submit">Registrar Empréstimo</button></form>
    <form action="registrar_aluno.php" method="get"><button type="submit">Registrar Aluno</button></form>
    <form action="ver_livro.php" method="get"><button type="submit">ver Livros</button></form>
    <form action="buscar_livros.php" method="get"><button type="submit">Buscar Livros</button></form>
    <form action="registrar_professor.php" method="get"><button type="submit">Registrar Professor</button></form>
    <form action="relatorio.php" method="get"><button type="submit">Relatório</button></form>
</div>

<!-- Conteúdo principal -->
<div class="main-content">
    <h1>Registrar Empréstimo</h1>

    <!-- Mensagens de sucesso ou erro -->
    <?php if ($msg): ?>
        <div class="message"><?= $msg ?></div>
    <?php endif; ?>

    <!-- Formulário de registro -->
    <form action="registrar_emprestimo.php" method="post" class="form-emprestimo">
        <label for="professor">Professor Responsável:</label>
        <select name="professor_id" id="professor" required>
            <?php while ($row = $professores->fetch()): ?>
                <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['nome']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="aluno_nome">Aluno:</label>
<div style="position: relative;">
    <input type="text" id="aluno_nome" name="aluno_nome" autocomplete="off" required />
    <input type="hidden" id="aluno_id" name="aluno_id" required />
    <div id="sugestoes_aluno" class="sugestoes-box"></div>
</div>


        </select>

        <label for="livro">Livro:</label>
        <select name="livro_id" id="livro" required>
            <?php while ($row = $livros->fetch()): ?>
                <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['nome_livro']) ?></option>
            <?php endwhile; ?>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('aluno_nome');
    const sugestoesBox = document.getElementById('sugestoes_aluno');
    const hiddenId = document.getElementById('aluno_id');

    input.addEventListener('input', function () {
        const query = input.value.trim();

        if (query.length === 0) {
            sugestoesBox.innerHTML = '';
            hiddenId.value = '';
            return;
        }

        fetch(`buscar_alunos.php?q=${encodeURIComponent(query)}`)
            .then(res => res.text())
            .then(html => {
                sugestoesBox.innerHTML = html;
                sugestoesBox.style.display = 'block';
            });
    });

    // Evento de clique nas sugestões
    sugestoesBox.addEventListener('click', function (e) {
        if (e.target.classList.contains('sugestao-item')) {
            input.value = e.target.dataset.nome;
            hiddenId.value = e.target.dataset.id;
            sugestoesBox.innerHTML = '';
        }
    });

    // Esconde sugestões se clicar fora
    document.addEventListener('click', function (e) {
        if (!sugestoesBox.contains(e.target) && e.target !== input) {
            sugestoesBox.innerHTML = '';
        }
    });
});
</script>
