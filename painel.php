<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Define o tipo de codificação de caracteres -->
    <meta charset="UTF-8" />
    <!-- Define a escala inicial para dispositivos móveis -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Título da aba do navegador -->
    <title>Biblioteca</title>
    <!-- Link para o arquivo de estilos externo -->
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

<!-- Menu lateral da aplicação -->
<div class="sidebar">

    <!-- Botão para voltar ao painel principal -->
    <form action="painel.php" method="get">
        <button type="submit">🏠 Casa</button>
    </form>

    <!-- Título do menu -->
    <h2>Menu</h2>

    <!-- Botão para ver empréstimos -->
    <form action="ver_emprestimos.php" method="get">
        <button type="submit">Ver Empréstimos</button>
    </form>

    <!-- Botão para registrar um novo empréstimo -->
    <form action="registrar_emprestimo.php" method="get">
        <button type="submit">Registrar Empréstimo</button>
    </form>

    <!-- Botão para registrar um novo aluno -->
    <form action="registrar_aluno.php" method="get">
        <button type="submit">Registrar Aluno</button>
    </form>

    <!-- Botão para ver registros de livros por professores -->
    <form action="ver_professores.php" method="get">
        <button type="submit">Ver registros de livros</button>
    </form>

    <!-- Botão para buscar livros -->
    <form action="buscar_livros.php" method="get">
        <button type="submit">Buscar Livros</button>
    </form>

    <!-- NOVO: Botão para registrar professor -->
    <form action="registrar_professor.php" method="get">
        <button type="submit">Registrar Professor</button>
    </form>

    <!-- NOVO: Botão para acessar a página de relatório -->
    <form action="relatorio.php" method="get">
        <button type="submit">Relatório</button>
    </form>

</div>

<!-- Conteúdo principal da página -->
<div class="main-content">
    <!-- Título de boas-vindas -->
    <h1>Bem-vindo ao Sistema de Biblioteca</h1>

    <!-- Tabela simples com instrução para o usuário -->
    <table>
        <tr><td>Use o menu à esquerda para navegar</td></tr>
    </table>
</div>

</body>
</html>