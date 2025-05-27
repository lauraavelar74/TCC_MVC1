<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }

        h1 {
            margin-bottom: 30px;
            color: #ffffff;
            text-shadow: 2px 2px 5px #000000aa;
        }

        form {
            margin: 5px;
        }

        button {
            padding: 15px 50px;
            font-size: 20px;
            background-color: rgba(255, 182, 193, 0.9); /* rosa claro com transparência */
            color: #000;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        button:hover {
            background-color: rgba(255, 140, 160, 0.9); /* tom um pouco mais escuro */
        }
    </style>
</head>
<body>

<h1>Bem-vindo ao Sistema de Biblioteca</h1>

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

</body>
</html>
