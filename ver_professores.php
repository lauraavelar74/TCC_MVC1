<?php
session_start();
if (!isset($_SESSION['cpf'])) {
    header("Location: index.php"); // Redireciona para a página de login caso não esteja autenticado
    exit();
}

$conn = new mysqli("localhost", "root", "", "biblioteca_mvc"); // Conexão com o banco de dados
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$cpf = $_SESSION['cpf'];
$sql = "SELECT cpf FROM professores WHERE cpf = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cpf);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    session_destroy();
    header("Location: index.php");
    exit();
}
$stmt->close();

// Consulta para buscar os professores cadastrados
$sql_professores = "SELECT cpf, nome, email FROM professores ORDER BY nome ASC";
$result_professores = $conn->query($sql_professores);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Professores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        a {
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
        }
        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Lista de Professores</h2>
    <table>
        <thead>
            <tr>
                <th>CPF</th>
                <th>Nome</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_professores->num_rows > 0) {
                while ($row = $result_professores->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['cpf']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Nenhum professor cadastrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="painel.php">Voltar para o painel</a>
</body>
</html>

<?php
$conn->close();
?>
