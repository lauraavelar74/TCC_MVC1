<?php
// Conexão com o banco de dados usando PDO
$pdo = new PDO("mysql:host=localhost;dbname=biblioteca_mvc;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retorna os resultados como array associativo
]);

// Variável para armazenar mensagens (de sucesso ou erro)
$msg = '';

// Verifica se o formulário foi enviado via POST e se todos os campos obrigatórios estão presentes
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cpf"], $_POST["nome"], $_POST["email"], $_POST["senha"])) {
    // Remove espaços em branco dos dados recebidos
    $cpf = trim($_POST["cpf"]);
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    // Criptografa a senha para segurança
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    // Verifica se nenhum dos campos está vazio
    if ($cpf && $nome && $email && $_POST["senha"]) {
        // Verifica se já existe um professor com o mesmo CPF
        $verifica = $pdo->prepare("SELECT COUNT(*) FROM professores WHERE cpf = :cpf");
        $verifica->execute([':cpf' => $cpf]);

        // Se já existir, exibe uma mensagem de erro
        if ($verifica->fetchColumn() > 0) {
            $msg = "Erro: CPF já cadastrado!";
        } else {
            // Caso contrário, insere o novo professor no banco de dados
            $stmt = $pdo->prepare("INSERT INTO professores (cpf, nome, email, senha) VALUES (:cpf, :nome, :email, :senha)");
            $stmt->execute([
                ':cpf' => $cpf,
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha
            ]);
            // Mensagem de sucesso
            $msg = "Professor registrado com sucesso!";
        }
    } else {
        // Mensagem de erro caso algum campo esteja vazio
        $msg = "Todos os campos são obrigatórios!";
    }
}
?>

<!-- Início do HTML -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Define o conjunto de caracteres -->
    <meta charset="UTF-8" />
    <!-- Ajusta a visualização em dispositivos móveis -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Título da aba -->
    <title>Registrar Professor</title>
    <!-- Importa o arquivo de estilos externo -->
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

<!-- Início do menu lateral (sidebar) -->
<div class="sidebar">
    <!-- Título do menu -->
    <h2>Menu</h2>
    <!-- Botão para voltar ao painel inicial -->
    <form action="painel.php" method="get">
        <button type="submit">🏠 Casa</button>
    </form>
    <!-- Botões para navegar entre as páginas do sistema -->
    <form action="ver_emprestimos.php" method="get"><button type="submit">Ver Empréstimos</button></form>
    <form action="registrar_emprestimo.php" method="get"><button type="submit">Registrar Empréstimo</button></form>
    <form action="registrar_aluno.php" method="get"><button type="submit">Registrar Aluno</button></form>
    <form action="ver_livro.php" method="get"><button type="submit">ver Livros</button></form>
    <form action="buscar_livros.php" method="get"><button type="submit">Buscar Livros</button></form>
    <form action="registrar_professor.php" method="get"><button type="submit">Registrar Professor</button></form>
    <form action="relatorio.php" method="get"><button type="submit">Relatório</button></form>
</div>

<!-- Área principal de conteúdo -->
<div class="main-content">
    <!-- Título da seção -->
    <h2>Registrar Professor</h2>

    <!-- Exibe a mensagem de sucesso ou erro, se existir -->
    <?php if ($msg): ?>
        <p class="mensagem"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <!-- Formulário de cadastro de professor -->
    <form action="registrar_professor.php" method="POST" class="cadastro-aluno">
        <!-- Campo CPF -->
        <label for="cpf">CPF:</label>
        <input type="text" maxlength="11" id="cpf" name="cpf" required />

        <!-- Campo Nome -->
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required />

        <!-- Campo Email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required />

        <!-- Campo Senha -->
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required />

        <!-- Botão para enviar o formulário -->
        <button type="submit">Registrar</button>
    </form>
</div>

</body>
</html>
