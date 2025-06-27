<?php
// Conexão com o banco de dados usando PDO
$pdo = new PDO("mysql:host=localhost;dbname=biblioteca_mvc;charset=utf8mb4", 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,       // Ativa mensagens de erro para exceções
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Retorna os dados como arrays associativos
]);

// Variável para mensagens de sucesso ou erro
$msg = '';

// Verifica se o formulário foi enviado por método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura e limpa os dados enviados pelo formulário
    $nome = trim($_POST["nome"] ?? '');
    $serie = trim($_POST["serie"] ?? '');
    $email = trim($_POST["email"] ?? '');

    // Verifica se todos os campos foram preenchidos
    if ($nome && $serie && $email) {
        try {
            // Prepara a instrução SQL para inserir o aluno
            $stmt = $pdo->prepare("INSERT INTO alunos (nome, serie, email) VALUES (:nome, :serie, :email)");

            // Executa o comando SQL com os valores fornecidos
            $stmt->execute([
                ':nome' => $nome,
                ':serie' => $serie,
                ':email' => $email
            ]);

            // Define a mensagem de sucesso
            $msg = "Aluno cadastrado com sucesso!";
        } catch (PDOException $e) {
            // Em caso de erro no banco, exibe a mensagem
            $msg = "Erro ao cadastrar: " . $e->getMessage();
        }
    } else {
        // Caso algum campo esteja vazio, exibe aviso
        $msg = "Preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrar Aluno</title>
    <!-- Importa o arquivo CSS com os estilos -->
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

<!-- Menu lateral (sidebar) -->
<div class="sidebar">
    <!-- Botão para voltar à tela inicial -->
    <form action="painel.php" method="get">
        <button type="submit">🏠 Casa</button>
    </form>

    <!-- Título do menu -->
    <h2>Menu</h2>

    <!-- Botões de navegação para as demais páginas -->
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
    <form action="registrar_professor.php" method="get">
        <button type="submit">Registrar Professor</button>
    </form>
    <form action="relatorio.php" method="get">
        <button type="submit">Relatório</button>
    </form>
</div>

<!-- Conteúdo principal da página -->
<div class="main-content">
    <!-- Título da página -->
    <h1>Cadastro de Aluno</h1>

    <!-- Exibe mensagem de sucesso ou erro, se houver -->
    <?php if ($msg): ?>
        <div class="message"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- Formulário de cadastro do aluno -->
    <form method="post" class="cadastro-aluno">
        <!-- Campo para nome do aluno -->
        <label for="nome">Nome do aluno:</label>
        <input type="text" id="nome" name="nome" required>

        <!-- Campo para série -->
        <label for="serie">Série:</label>
        <input type="text" id="serie" name="serie" required>

        <!-- Campo para email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <!-- Botão para enviar o formulário -->
        <button type="submit">Salvar</button>

        <!-- Link oculto para voltar ao painel -->
        <a class="voltar" href="painel.php">Voltar</a>
    </form>
</div>

</body>
</html>
