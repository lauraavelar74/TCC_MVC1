<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Relatório de Empréstimos</title>
    <link rel="stylesheet" href="styles.css" />

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        // Dados simulados dos alunos que mais pegaram livros
        var dataAlunos = google.visualization.arrayToDataTable([
            ['Aluno', 'Quantidade de Empréstimos'],
            ['João', 15],
            ['Maria', 12],
            ['Pedro', 10],
            ['Ana', 8],
            ['Lucas', 5]
        ]);

        // Dados simulados dos livros mais emprestados
        var dataLivros = google.visualization.arrayToDataTable([
            ['Livro', 'Quantidade de Empréstimos'],
            ['Dom Quixote', 20],
            ['O Pequeno Príncipe', 18],
            ['Harry Potter', 16],
            ['1984', 12],
            ['O Senhor dos Anéis', 10]
        ]);

        var optionsAlunos = {
            title: 'Alunos que mais pegaram livros emprestados',
            pieHole: 0.4,
            width: 450,
            height: 350
        };

        var optionsLivros = {
            title: 'Livros mais emprestados',
            pieHole: 0.4,
            width: 450,
            height: 350
        };

        var chartAlunos = new google.visualization.PieChart(document.getElementById('chart_alunos'));
        chartAlunos.draw(dataAlunos, optionsAlunos);

        var chartLivros = new google.visualization.PieChart(document.getElementById('chart_livros'));
        chartLivros.draw(dataLivros, optionsLivros);
    }
    </script>
</head>
<body>

    <!-- Sidebar igual ao buscar_livro.php -->
    <div class="sidebar">
        <form action="painel.php" method="get">
            <button type="submit">🏠 Casa</button>
        </form>

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
        <form action="registrar_professor.php" method="get">
            <button type="submit">Registrar Professor</button>
        </form>
        <form action="relatorio.php" method="get">
            <button type="submit">Relatório</button>
        </form>
    </div>

    <!-- Conteúdo principal -->
    <div class="main-content">
    <h2>Gráficos de Empréstimos</h2>
    <div class="relatorio-graficos">
        <div id="chart_alunos" class="relatorio-grafico"></div>
        <div id="chart_livros" class="relatorio-grafico"></div>
    </div>
</div>
</body>
</html>
