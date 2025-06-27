<?php
// Inicia a sessão
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: index.php");
    exit;
}

// Obtém o nome do professor logado
$professor_nome = $_SESSION['professor_nome'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Biblioteca</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

<div class="sidebar">
        <h2>Menu</h2>
    <form action="painel.php" method="get">
        <button type="submit">🏠 Casa</button>
    </form>
    <form action="ver_emprestimos.php" method="get">
        <button type="submit">Ver Empréstimos</button>
    </form>
    <form action="registrar_emprestimo.php" method="get">
        <button type="submit">Registrar Empréstimo</button>
    </form>
    <form action="registrar_aluno.php" method="get">
        <button type="submit">Registrar Aluno</button>
    </form>
    <form action="ver_livro.php" method="get">
        <button type="submit">Ver livros</button>
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

<div class="main-content">
    <h1>Bem-vindo, <?php echo htmlspecialchars($professor_nome); ?>!</h1>
    <table>
        <tr><td>Use o menu à esquerda para navegar</td></tr>
    </table>
</div>

</body>
</html>
