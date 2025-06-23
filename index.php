<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Login Professor - MVC Biblioteca</title>
<style>
  /* Reset básico */
  * {
    box-sizing: border-box;
  }
  body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: Arial, sans-serif;
    background-image: url('./img/hello.jpg');
    background-size: cover;
    background-position: center;
    color: #fff;
  }

  /* Container principal: altura total da viewport */
  .main-container {
    min-height: 100vh;
    display: flex;
    background: rgba(0,0,0,0.6); /* um filtro escuro por cima da imagem para dar contraste */
  }

  /* Coluna esquerda: texto grande */
  .left-side {
    flex: 1;
    padding: 60px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: rgba(255, 105, 180, 0.3); /* rosa transparente para destaque */
  }

  .left-side h1 {
    font-size: 3.5rem;
    margin: 0 0 20px 0;
    line-height: 1.2;
    font-weight: 900;
    text-shadow: 2px 2px 4px #900046;
  }

  .left-side h2 {
    font-size: 2.5rem;
    margin: 0 0 15px 0;
    font-weight: 700;
    text-shadow: 1.5px 1.5px 3px #900046;
  }

  /* Coluna direita: formulário de login */
  .right-side {
    flex: 1;
    background-color: rgba(255, 255, 255, 0.95);
    color: #d6336c;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
  }

  .login-container {
    width: 100%;
    max-width: 380px;
    text-align: center;
  }

  .login-container h2 {
    margin-bottom: 25px;
    color: #d6336c;
  }

  .login-container input[type="text"],
  .login-container input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0 20px 0;
    border: 2px solid #d6336c;
    border-radius: 6px;
    font-size: 16px;
  }

  .login-container button {
    background-color: #d6336c;
    border: none;
    color: white;
    padding: 12px 0;
    width: 100%;
    border-radius: 6px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .login-container button:hover {
    background-color: #ff66b2;
  }

  .login-container a {
    display: block;
    margin-top: 20px;
    color: #a83264;
    text-decoration: none;
    font-weight: 600;
  }

  .login-container a:hover {
    text-decoration: underline;
  }

  /* Área do gráfico para rolar para baixo */
  .chart-section {
    background: white;
    color: #333;
    padding: 40px 20px;
    text-align: center;
  }

  .chart-section h3 {
    margin-bottom: 25px;
    color: #d6336c;
  }

  /* Responsivo - em telas menores empilha */
  @media (max-width: 900px) {
    .main-container {
      flex-direction: column;
    }

    .left-side, .right-side {
      flex: none;
      width: 100%;
      padding: 30px 20px;
    }

    .left-side h1 {
      font-size: 2.5rem;
    }
    .left-side h2 {
      font-size: 1.8rem;
    }
  }
</style>
</head>
<body>

<div class="main-container">
  <div class="left-side">
    <h1>Bem-vindo à biblioteca</h1>
    <h2>do MVC</h2>
  </div>

  <div class="right-side">
    <div class="login-container">
      <h2>Login de Professor</h2>
      <form method="post" action="login.php">
        <input type="text" name="cpf" placeholder="CPF" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Login</button>
      </form>
      <a href="./backend/registrar_professor.php">Não tem uma conta? Cadastre-se</a>
    </div>
  </div>
</div>

<!-- Seção que aparece quando rolar pra baixo -->
<section class="chart-section">
  <h3>Gráficos dos Empréstimos</h3>
  <p>Aqui vai o gráfico de pizza com os livros mais emprestados e alunos que mais pegaram livros.</p>
  <!-- Você poderá colocar o gráfico aqui depois -->
</section>

</body>
</html>

<?php
include 'db.php';

// Configurações PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Define o mês atual (exemplo: 2025-06)
$mes_atual = date('Y-m');

// 1) Dados para gráfico 1 - quantidade de livros diferentes emprestados por nome do aluno
$sql_alunos = "
  SELECT a.nome, COUNT(DISTINCT e.id_livro) AS livros_diferentes
  FROM emprestimos e
  JOIN alunos a ON e.id_aluno = a.id
  WHERE DATE_FORMAT(e.data_emprestimo, '%Y-%m') = :mes
  GROUP BY a.nome
  ORDER BY livros_diferentes DESC
  LIMIT 10
";
$stmt = $pdo->prepare($sql_alunos);
$stmt->execute(['mes' => $mes_atual]);
$dados_alunos = $stmt->fetchAll();

// 2) Dados para gráfico 2 - quantidade de alunos diferentes que pegaram cada livro
$sql_livros = "
  SELECT l.nome_livro, COUNT(DISTINCT e.id_aluno) AS alunos_diferentes
  FROM emprestimos e
  JOIN livros l ON e.id_livro = l.id
  WHERE DATE_FORMAT(e.data_emprestimo, '%Y-%m') = :mes
  GROUP BY l.nome_livro
  ORDER BY alunos_diferentes DESC
  LIMIT 10
";
$stmt2 = $pdo->prepare($sql_livros);
$stmt2->execute(['mes' => $mes_atual]);
$dados_livros = $stmt2->fetchAll();

// Preparar dados para JS
$labels_alunos = array_column($dados_alunos, 'nome');
$valores_alunos = array_column($dados_alunos, 'livros_diferentes');

$labels_livros = array_column($dados_livros, 'nome_livro');
$valores_livros = array_column($dados_livros, 'alunos_diferentes');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Gráficos de Empréstimos</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #fff0f6;
      color: #6f2a47;
    }
    h2 {
      color: #d6336c;
      margin-bottom: 10px;
    }
    .charts-container {
      display: flex;
      gap: 40px;
      flex-wrap: wrap;
    }
    .chart-box {
      flex: 1 1 400px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(214, 51, 108, 0.3);
      padding: 20px;
      text-align: center;
    }
    canvas {
      max-width: 100%;
      height: 300px;
    }
  </style>
</head>
<body>

  <h2>Alunos com mais livros diferentes emprestados no mês <?= htmlspecialchars($mes_atual) ?></h2>
  <div class="charts-container">
    <div class="chart-box">
      <canvas id="chartAlunos"></canvas>
    </div>

    <div class="chart-box">
      <h2>Livros com mais alunos distintos no mês <?= htmlspecialchars($mes_atual) ?></h2>
      <canvas id="chartLivros"></canvas>
    </div>
  </div>

  <script>
    // Dados do PHP para JS
    const labelsAlunos = <?= json_encode($labels_alunos) ?>;
    const valoresAlunos = <?= json_encode($valores_alunos) ?>;

    const labelsLivros = <?= json_encode($labels_livros) ?>;
    const valoresLivros = <?= json_encode($valores_livros) ?>;

    // Cores rosa para as fatias
    const cores = [
      '#d6336c', '#ff66b2', '#f8bbd0', '#a83264', '#f06292',
      '#e91e63', '#c2185b', '#ad1457', '#880e4f', '#f48fb1'
    ];

    // Gráfico 1 - Alunos
    const ctxAlunos = document.getElementById('chartAlunos').getContext('2d');
    const chartAlunos = new Chart(ctxAlunos, {
      type: 'pie',
      data: {
        labels: labelsAlunos,
        datasets: [{
          data: valoresAlunos,
          backgroundColor: cores,
          borderColor: '#fff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' },
          title: {
            display: false,
          }
        }
      }
    });

    // Gráfico 2 - Livros
    const ctxLivros = document.getElementById('chartLivros').getContext('2d');
    const chartLivros = new Chart(ctxLivros, {
      type: 'pie',
      data: {
        labels: labelsLivros,
        datasets: [{
          data: valoresLivros,
          backgroundColor: cores,
          borderColor: '#fff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' },
          title: {
            display: false,
          }
        }
      }
    });
  </script>

</body>
</html>
