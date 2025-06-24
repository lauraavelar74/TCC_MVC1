<?php
include 'db.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$mes = date('Y-m');

// Consulta 1: alunos com mais livros diferentes
$stmt = $pdo->prepare("
  SELECT a.nome, COUNT(DISTINCT e.id_livro) AS total
  FROM emprestimos e JOIN alunos a ON e.id_aluno = a.id
  WHERE DATE_FORMAT(e.data_emprestimo, '%Y-%m') = :mes
  GROUP BY a.nome ORDER BY total DESC LIMIT 10
");
$stmt->execute(['mes' => $mes]);
$alunos = $stmt->fetchAll();

// Consulta 2: livros com mais alunos distintos
$stmt = $pdo->prepare("
  SELECT l.nome_livro, COUNT(DISTINCT e.id_aluno) AS total
  FROM emprestimos e JOIN livros l ON e.id_livro = l.id
  WHERE DATE_FORMAT(e.data_emprestimo, '%Y-%m') = :mes
  GROUP BY l.nome_livro ORDER BY total DESC LIMIT 10
");
$stmt->execute(['mes' => $mes]);
$livros = $stmt->fetchAll();

$labelsAlunos = array_column($alunos, 'nome');
$valoresAlunos = array_column($alunos, 'total');
$labelsLivros = array_column($livros, 'nome_livro');
$valoresLivros = array_column($livros, 'total');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Login Professor - Biblioteca MVC</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * { box-sizing: border-box; }
    body, html {
      margin: 0; padding: 0; height: 100%;
      font-family: Arial, sans-serif;
      background: url('./img/hello.jpg') center/cover no-repeat;
      color: #fff;
    }
    .main-container {
      min-height: 100vh; display: flex;
      background: rgba(0,0,0,0.6);
      flex-wrap: wrap;
    }
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
    .right-side {
      background: rgba(255,255,255,0.95); color: #d6336c; align-items: center;
    }
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
    a { margin-top: 15px; color: #a83264; text-decoration: none; font-weight: 600; display: block; }
    a:hover { text-decoration: underline; }
    .chart-section {
      width: 100%; background: white; color: #333; padding: 40px 20px; text-align: center;
    }
    .charts-container {
      display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;
    }
    .chart-box {
      flex: 1 1 400px; background: #fff; padding: 20px;
      border-radius: 10px; box-shadow: 0 4px 10px rgb(255, 0, 111);
    }
    canvas { max-width: 70%; height: 200px; }
    @media (max-width: 900px) {
      .main-container { flex-direction: column; }
      .left-side h1 { font-size: 2rem; }
      .left-side h2 { font-size: 1.5rem; }
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

<section class="chart-section">
  <h2>Estatísticas de Empréstimos - <?= htmlspecialchars($mes) ?></h2>
  <div class="charts-container">
    <div class="chart-box">
      <h3>leitores em destaque</h3>
      <canvas id="chartAlunos"></canvas>
    </div>
    <div class="chart-box">
      <h3>Livros mais emprestados</h3>
      <canvas id="chartLivros"></canvas>
    </div>
  </div>
</section>

<script>
  const labelsAlunos = <?= json_encode($labelsAlunos) ?>;
  const valoresAlunos = <?= json_encode($valoresAlunos) ?>;
  const labelsLivros = <?= json_encode($labelsLivros) ?>;
  const valoresLivros = <?= json_encode($valoresLivros) ?>;
  const cores = ['#d6336c', '#ff66b2', '#f8bbd0', '#a83264', '#f06292', '#e91e63', '#c2185b', '#ad1457', '#880e4f', '#f48fb1'];

  const legendaEstilizada = {
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          color: '#333',
          font: {
            size: 14,
            weight: 'bold'
          },
          padding: 20
        }
      }
    }
  };

  new Chart(document.getElementById('chartAlunos'), {
    type: 'pie',
    data: {
      labels: labelsAlunos,
      datasets: [{
        data: valoresAlunos,
        backgroundColor: cores
      }]
    },
    options: legendaEstilizada
  });

  new Chart(document.getElementById('chartLivros'), {
    type: 'pie',
    data: {
      labels: labelsLivros,
      datasets: [{
        data: valoresLivros,
        backgroundColor: cores
      }]
    },
    options: legendaEstilizada
  });
</script>
</body>
</html>
