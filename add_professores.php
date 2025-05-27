<?php

include 'db.php';

if (isset($_POST['register'])) {
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    // Criptografando a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Verificando se o CPF já existe
    $checkSql = "SELECT * FROM professores WHERE cpf = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "O CPF já está registrado!";
    } else {
        // Inserir o novo registro
        $sql = "INSERT INTO professores (cpf, senha) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $cpf, $senhaHash);

        if ($stmt->execute()) {
            echo "Registro realizado com sucesso!";
        } else {
            echo "Erro no registro: " . $stmt->error;
        }
    }
    $stmt->close();
}
?>
