<?php

include 'db.php';

// Conectar ao banco de dados
$conn = new mysqli("localhost", "root", "", "biblioteca_mvc");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    // Exemplo de registro de usuário
    //$senha = password_hash('sua_senha', PASSWORD_DEFAULT); // Cria o hash da senha
    //var_dump($senha); // Exibe o hash gerado para depuração

    // Código para armazenar $senha no banco de dados
    $sql = "SELECT senha FROM professores WHERE cpf = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_senha); // Aqui, você está pegando o hash da senha armazenada
    $stmt->fetch();
    //var_dump($stmt);
    // Verificar se o CPF existe e a senha está correta
    if ($stmt->num_rows > 0) {
        var_dump($stmt->num_rows);
        if (password_verify($senha, $hashed_senha)) {
            $_SESSION['cpf'] = $cpf; // Salva o CPF na sessão
            header("Location: painel.php"); // Redireciona para o painel
            exit(); // Encerra o script após o redirecionamento
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "CPF não encontrado.";
     
    
    }

    $stmt->close();

$conn->close();
?>