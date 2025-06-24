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
    <meta charset="UTF-8">
    <title>Registrar Empréstimo de Livro</title>
    <style>
        /* Layout com sidebar igual painel */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            background-color: #ffe4e1; /* rosa claro */
            color: #000;
        }

        /* Sidebar igual painel.php */
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

        /* Conteúdo principal ao lado da sidebar */
        .main-content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
            background: #fff0f6;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(255, 105, 180, 0.3);
            max-width: 600px;
            margin: 40px;
        }

        h2 {
            text-align: center;
            color: #d6336c;
            margin-bottom: 25px;
        }

        form table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
        }

        form label {
            color: #a83264;
            font-weight: bold;
        }

        form select, form input[type="date"] {
            width: 100%;
            padding: 8px 10px;
            border: 2px solid #d6336c;
            border-radius: 6px;
            background-color: #fff0f6;
            color: #6f2a47;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        form select:focus, form input[type="date"]:focus {
            border-color: #ff66b2;
        }

        form input[type="submit"] {
            background-color: #d6336c;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #ff66b2;
        }

        .success {
            color: #2f9e44;
            background-color: #d3f9d8;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }

        .error {
            color: #c92a2a;
            background-color: #ffe3e3;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }

        /* Container dos botões "Registrar" e "Voltar" lado a lado */
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-group a {
            flex: 1;
            background-color: rgb(255, 0, 115);
            color: white;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            display: inline-block;
            line-height: 1.2;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-group a:hover {
            background-color: #ff4ca1;
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
    <h2>Registrar Empréstimo</h2>

    <?php if (!empty($msg)) echo $msg; ?>

    <form action="registrar_emprestimo.php" method="post">
        <table>
            <tr>
                <td><label for="professor">Professor Responsável:</label></td>
                <td>
                    <select name="professor_id" id="professor" required>
                        <?php while ($row = $professores->fetch()) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nome']); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td><label for="aluno">Aluno:</label></td>
                <td>
                    <select name="aluno_id" id="aluno" required>
                        <?php while ($row = $alunos->fetch()) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nome']); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td><label for="livro">Livro:</label></td>
                <td>
                    <select name="livro_id" id="livro" required>
                        <?php while ($row = $livros->fetch()) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nome_livro']); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td><label for="data_emprestimo">Data de Empréstimo:</label></td>
                <td><input type="date" name="data_emprestimo" id="data_emprestimo" required></td>
            </tr>

            <tr>
                <td><label for="data_devolucao">Data de Devolução:</label></td>
                <td><input type="date" name="data_devolucao" id="data_devolucao" required></td>
            </tr>
        </table>

        <div class="btn-group">
            <input type="submit" value="Registrar Empréstimo" />
            <a href="painel.php">Voltar ao Painel</a>
        </div>
    </form>
</div>

</body>
</html>
