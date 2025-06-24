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
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-image: url('https://wallpapers.com/images/hd/pink-chococat-170lafkb61ectzot.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: #000; /* texto preto para o corpo */
        text-shadow: none; /* removido sombra do texto */
    }

    h1 {
        margin-bottom: 30px;
        font-size: 28px;
        color: #000; /* título preto */
        text-shadow: none;
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

    form {
        margin: 0;
    }

    button {
        width: 200px;
        padding: 12px 15px;
        font-size: 16px;
        background-color: rgba(255, 105, 180, 0.9);
        color: #000; /* texto preto */
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 3px 6px rgba(255, 105, 180, 0.7);
    }

    button:hover {
        background-color: rgba(219, 112, 147, 0.9);
        box-shadow: 0 5px 10px rgba(219, 112, 147, 0.9);
    }
</style>
</head>
<body>

<h1>Bem-vindo ao Sistema de Biblioteca</h1>

<table>
    <tr>
        <td>
            <form action="ver_emprestimos.php" method="get">
                <button type="submit">Ver Empréstimos</button>
            </form>
        </td>
    </tr>
    <tr>
        <td>
            <form action="registrar_emprestimo.php" method="get">
                <button type="submit">Registrar Empréstimo</button>
            </form>
        </td>
    </tr>
    <tr>
        <td>
            <form action="registrar_aluno.php" method="get">
                <button type="submit">Registrar Aluno</button>
            </form>
        </td>
    </tr>
    <tr>
        <td>
            <form action="registrar_livro.php" method="get">
                <button type="submit">Registrar Livros</button>
            </form>
        </td>
    </tr>
    <tr>
        <td>
            <form action="buscar_livros.php" method="get">
                <button type="submit">Buscar Livros</button>
            </form>
        </td>
    </tr>
</table>

</body>
</html>
