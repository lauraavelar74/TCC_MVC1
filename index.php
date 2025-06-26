<?php
// Inclui o arquivo de conexão com o banco de dados
include 'db.php';

// Configurações de erro e modo de retorno de dados para o PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Captura o mês atual no formato "YYYY-MM"
$mes = date('Y-m');

// Consulta os alunos que pegaram mais livros diferentes no mês atual
$stmt = $pdo->prepare("
  SELECT a.nome, COUNT(DISTINCT e.id_livro) AS total
  FROM emprestimos e JOIN alunos a ON e.id_aluno = a.id
  WHERE DATE_FORMAT(e.data_emprestimo, '%Y-%m') = :mes
  GROUP BY a.nome ORDER BY total DESC LIMIT 10
");
// Executa a consulta com o parâmetro do mês atual
$stmt->execute(['mes' => $mes]);
// Armazena os resultados
$alunos = $stmt->fetchAll();

// Consulta os livros que foram emprestados por mais alunos diferentes no mês atual
$stmt = $pdo->prepare("
  SELECT l.nome_livro, COUNT(DISTINCT e.id_aluno) AS total
  FROM emprestimos e JOIN livros l ON e.id_livro = l.id
  WHERE DATE_FORMAT(e.data_emprestimo, '%Y-%m') = :mes
  GROUP BY l.nome_livro ORDER BY total DESC LIMIT 10
");
// Executa a consulta
$stmt->execute(['mes' => $mes]);
// Armazena os resultados
$livros = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Login Professor - Biblioteca MVC</title>
  <style>
    /* Estilos básicos e fundo da página */
    * { box-sizing: border-box; }
    body, html {
      margin: 0; padding: 0; height: 100%;
      font-family: Arial, sans-serif;
      background: url('./img/hello.jpg') center/cover no-repeat;
      color: #fff;
    }

    /* Container principal da página */
    .main-container {
      min-height: 100vh; display: flex;
      background: rgba(0,0,0,0.6);
      flex-wrap: wrap;
    }

    /* Estilização do lado esquerdo (boas-vindas) */
    .left-side, .right-side {
      flex: 1; padding: 40px;
      display: flex; flex-direction: column; justify-content: center;
    }

    .left-side {
      background: rgba(255, 0, 140, 0.59);
    }

    .left-side h1, .left-side h2 {
      margin: 0; text-shadow: 2px 2px 4px #900046;
    }

    .left-side h1 { font-size: 3rem; font-weight: 900; }
    .left-side h2 { font-size: 2rem; font-weight: 700; }

    /* Estilização do lado direito (formulário de login) */
    .right-side {
      background: rgba(255,255,255,0.95); color: #d6336c; align-items: center;
    }

    /* Container do formulário */
    .login-container {
      width: 100%; max-width: 380px; text-align: center;
    }

    .login-container input, .login-container button {
      width: 100%; padding: 10px; margin: 10px 0;
      font-size: 16px; border-radius: 6px;
    }

    input { border: 2px solid #d6336c; }

    button {
      background: #d6336c; color: #fff; border: none;
      cursor: pointer; transition: 0.3s;
    }

    button:hover { background: #ff66b2; }

    /* Link de cadastro */
    a {
      margin-top: 15px; color: #a83264;
      text-decoration: none; font-weight: 600; display: block;
    }

    a:hover { text-decoration: underline; }

    /* Responsividade para telas menores */
    @media (max-width: 900px) {
      .main-container { flex-direction: column; }
      .left-side h1 { font-size: 2rem; }
      .left-side h2 { font-size: 1.5rem; }
    }
  </style>
</head>
<body>

<!-- Estrutura principal da página -->
<div class="main-container">
  <!-- Parte da esquerda com mensagem de boas-vindas -->
  <div class="left-side">
    <h1>Bem-vindo à biblioteca</h1>
    <h2>do MVC</h2>
  </div>

  <!-- Parte da direita com formulário de login -->
  <div class="right-side">
    <div class="login-container">
      <h2>Login de Professor</h2>
      <!-- Formulário de login -->
      <form method="post" action="login.php">
        <input type="text" name="cpf" placeholder="CPF" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Login</button>
      </form>
      <!-- Link para página de cadastro -->
      <a href="./backend/registrar_professor.php">Não tem uma conta? Cadastre-se</a>
    </div>
  </div>
</div>

</body>
</html>
