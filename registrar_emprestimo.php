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

            echo "<p class='success'>Empréstimo registrado com sucesso!</p>";
        } catch (PDOException $e) {
            echo "<p class='error'>Erro ao registrar empréstimo: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>Preencha todos os campos!</p>";
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
        body {
            font-family: Arial, sans-serif;
            background: #ffe6f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff0f6;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(255, 105, 180, 0.3);
            width: 400px;
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
    </style>
</head>
<body>

<div class="container">
    <h2>Registrar Empréstimo</h2>

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

        <input type="submit" value="Registrar Empréstimo">
    </form>
</div>

</body>
</html>
