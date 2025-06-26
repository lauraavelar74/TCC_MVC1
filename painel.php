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
    <form action="ver_professores.php" method="get">
        <button type="submit">Ver registros de livros</button>
    </form>
    <form action="buscar_livros.php" method="get">
        <button type="submit">Buscar Livros</button>
    </form>
</div>

<div class="main-content">
    <h1>Bem-vindo ao Sistema de Biblioteca</h1>
    <table>
        <tr><td>Use o menu à esquerda para navegar</td></tr>
    </table>
</div>

</body>
</html>
