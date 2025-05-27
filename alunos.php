<?php
// cadastrar_aluno.php - Cadastro de alunos

include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome']??'valor padrão';
    $serie = $_POST['serie'] ?? 'Valor padrão';
    $email = $_POST['email']??'valor padrão';

    $sql = "INSERT INTO alunos (nome, serie, email) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $serie, $email]);

    echo "Aluno cadastrado com sucesso!";
}
?>

<form method="POST">
    Nome: <input type="text" name="nome" required><br>
    Série: <input type="text" name="serie" required><br>
    Email: <input type="email" name="email" required><br>
    <button type="submit">Cadastrar Aluno</button>
</form>