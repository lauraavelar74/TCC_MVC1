<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Biblioteca</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        height: 100vh;
        display: flex;
        background-color: #ffe4e1; /* rosa claro */
        color: #000;
    }

    .sidebar {
        width: 220px;
        background-color: rgba(255, 182, 193, 0.8);
        padding: 30px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 3px 0 10px rgba(255, 105, 180, 0.6);
    }

    .sidebar h2 {
        font-size: 22px;
        margin-bottom: 30px;
        color: #000;
    }

    .sidebar form {
        width: 100%;
        margin-bottom: 15px;
    }

    .sidebar button {
        width: 100%;
        padding: 10px;
        font-size: 15px;
        background-color: rgba(255, 105, 180, 0.9);
        color: #000;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 3px 6px rgba(255, 105, 180, 0.7);
    }

    .sidebar button:hover {
        background-color: rgba(219, 112, 147, 0.9);
        box-shadow: 0 5px 10px rgba(219, 112, 147, 0.9);
    }

    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
    }

    h1 {
        margin-bottom: 30px;
        font-size: 28px;
        color: #000;
    }

    table {
        border-collapse: separate;
        border-spacing: 0 12px;
        background-color: rgba(255, 182, 193, 0.5);
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 5px 8px rgba(255, 182, 193, 0.7);
    }

    td {
        background-color: rgba(255, 192, 203, 0.9);
        border-radius: 10px;
        padding: 12px 40px;
        text-align: center;
        box-shadow: 0 3px 5px rgba(255, 105, 180, 0.5);
    }
</style>
</head>
<body>

<div class="sidebar">
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
    <h1>Bem-vindo ao Sistema de Biblioteca</h1>
    <table>
        <tr><td>Use o menu à esquerda para navegar</td></tr>
    </table>
</div>

</body>
</html>
