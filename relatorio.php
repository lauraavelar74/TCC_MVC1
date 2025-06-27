<?php
include('db.php'); // Faz a conexão com o banco usando PDO

// Consulta os 5 alunos que mais pegaram livros
$stmtAlunos = $pdo->prepare("
    SELECT a.nome AS aluno, COUNT(*) AS total
    FROM emprestimos e
    JOIN alunos a ON e.id_aluno = a.id
    GROUP BY e.id_aluno
    ORDER BY total DESC
    LIMIT 5
");
$stmtAlunos->execute(); // Executa a query
$alunos = $stmtAlunos->fetchAll(PDO::FETCH_ASSOC); // Recebe os resultados em array

// Consulta os 5 livros mais emprestados
$stmtLivros = $pdo->prepare("
    SELECT l.nome_livro AS livro, COUNT(*) AS total
    FROM emprestimos e
    JOIN livros l ON e.id_livro = l.id
    GROUP BY e.id_livro
    ORDER BY total DESC
    LIMIT 5
");
$stmtLivros->execute();
$livros = $stmtLivros->fetchAll(PDO::FETCH_ASSOC); // Recebe os resultados
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Relatório</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #ffeaf5;
      font-family: Arial, sans-serif;
      text-align: center;
      margin: 0;
      padding-bottom: 70px; /* para não sobrepor conteúdo com botão fixo */
    }
    h1 {
      color: #c2007a;
      margin-top: 20px;
    }
    .grafico-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
      margin-top: 30px;
    }
    .grafico-box {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 10px #ccc;
      width: 400px;
    }
    .btn-voltar {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #c2007a;
      color: white;
      border: none;
      padding: 12px 22px;
      border-radius: 30px;
      cursor: pointer;
      font-size: 16px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.3);
      transition: background-color 0.3s ease;
      z-index: 1000;
    }
    .btn-voltar:hover {
      background-color: #a10063;
    }
  </style>
</head>
<body>

  <h1>📊 Gráficos de Empréstimos</h1>

  <div class="grafico-container">
    <!-- Gráfico dos alunos -->
    <div class="grafico-box">
      <h4>Alunos que mais pegaram livros</h4>
      <canvas id="graficoAlunos"></canvas>
    </div>

    <!-- Gráfico dos livros -->
    <div class="grafico-box">
      <h4>Livros mais emprestados</h4>
      <canvas id="graficoLivros"></canvas>
    </div>
  </div>

  <button class="btn-voltar" onclick="history.back()">← Voltar</button>

  <script>
    const nomesAlunos = <?= json_encode(array_column($alunos, 'aluno')) ?>;
    const totalAlunos = <?= json_encode(array_column($alunos, 'total')) ?>;

    const ctxAlunos = document.getElementById('graficoAlunos');
    new Chart(ctxAlunos, {
      type: 'doughnut',
      data: {
        labels: nomesAlunos,
        datasets: [{
          label: 'Empréstimos',
          data: totalAlunos,
          backgroundColor: ['#4285F4', '#FBBC05', '#34A853', '#EA4335', '#9b59b6'],
        }]
      }
    });

    const nomesLivros = <?= json_encode(array_column($livros, 'livro')) ?>;
    const totalLivros = <?= json_encode(array_column($livros, 'total')) ?>;

    const ctxLivros = document.getElementById('graficoLivros');
    new Chart(ctxLivros, {
      type: 'doughnut',
      data: {
        labels: nomesLivros,
        datasets: [{
          label: 'Empréstimos',
          data: totalLivros,
          backgroundColor: ['#3498db', '#e67e22', '#1abc9c', '#e74c3c', '#8e44ad'],
        }]
      }
    });
  </script>

</body>
</html>
