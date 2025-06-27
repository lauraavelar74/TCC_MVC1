<?php
$pdo = new PDO("mysql:host=localhost;dbname=biblioteca_mvc;charset=utf8mb4", 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'], $_POST['isbn'], $_POST['autor'])) {
    $t = trim($_POST['titulo']);
    $i = trim($_POST['isbn']);
    $a = trim($_POST['autor']);

    if ($t && $i && $a) {
        $c = $pdo->prepare("SELECT COUNT(*) FROM livros WHERE isbn = :isbn");
        $c->execute([':isbn' => $i]);

        if (!$c->fetchColumn()) {
            $s = $pdo->prepare("INSERT INTO livros (nome_livro, isbn, nome_autor) VALUES (:t, :i, :a)");
            $s->execute([':t' => $t, ':i' => $i, ':a' => $a]);
            $msg = "Livro registrado: " . htmlspecialchars($t);
        } else {
            $msg = "Livro com esse ISBN já existe.";
        }
    } else {
        $msg = "Dados insuficientes.";
    }
}

if (isset($_GET['busca'])) {
    echo file_get_contents(
        "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($_GET['busca']) .
        "&startIndex=" . ($_GET['startIndex'] ?? 0) .
        "&maxResults=40"
    );
    exit;
}

if (isset($_GET['busca_local'])) {
    $q = trim($_GET['busca_local']);
    $campo = $_GET['campo'] ?? 'titulo';

    if (!$q) {
        echo json_encode([]);
    } else {
        $map = ['titulo' => 'nome_livro', 'autor' => 'nome_autor', 'isbn' => 'isbn'];
        $col = $map[$campo] ?? 'nome_livro';

        if ($campo === 'isbn') {
            $sql = "$col = :v";
            $params = [':v' => $q];
        } else {
            $palavras = preg_split('/\s+/', $q);
            $clausulas = [];
            $params = [];

            foreach ($palavras as $idx => $palavra) {
                $clausulas[] = "LOWER($col) LIKE LOWER(:v$idx)";
                $params[":v$idx"] = "%$palavra%";
            }

            $sql = implode(' AND ', $clausulas);
        }

        $r = $pdo->prepare("SELECT * FROM livros WHERE $sql");
        $r->execute($params);

        header('Content-Type: application/json');
        echo json_encode($r->fetchAll());
    }
    exit;
}
?>
<!-- Página de busca de livros -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Define o conjunto de caracteres -->
    <meta charset="UTF-8" />
    <!-- Responsividade para dispositivos móveis -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Título da página -->
    <title>Buscar Livros</title>
    <!-- Importa o CSS externo -->
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

<!-- Menu lateral (sidebar) -->
<div class="sidebar">

    <!-- Botão de retorno para a tela inicial -->
    <form action="painel.php" method="get">
        <button type="submit">🏠 Casa</button>
    </form>

    <!-- Título da seção de menu -->
    <h2>Menu</h2>

    <!-- Botão para visualizar empréstimos -->
    <form action="ver_emprestimos.php" method="get">
        <button type="submit">Ver Empréstimos</button>
    </form>

    <!-- Botão para registrar novos empréstimos -->
    <form action="registrar_emprestimo.php" method="get">
        <button type="submit">Registrar Empréstimo</button>
    </form>

    <!-- Botão para cadastrar novos alunos -->
    <form action="registrar_aluno.php" method="get">
        <button type="submit">Registrar Aluno</button>
    </form>

    <!-- Botão para cadastrar livros manualmente -->
    <form action="registrar_livro.php" method="get">
        <button type="submit">Registrar Livros</button>
    </form>

    <!-- Botão para buscar livros via API/local -->
    <form action="buscar_livros.php" method="get">
        <button type="submit">Buscar Livros</button>
    </form>

    <!-- Botão para cadastrar professor -->
    <form action="registrar_professor.php" method="get">
        <button type="submit">Registrar Professor</button>
    </form>

    <!-- Botão para acessar a página de relatórios -->
    <form action="relatorio.php" method="get">
        <button type="submit">Relatório</button>
    </form>

</div>

<!-- Área principal da aplicação -->
<div class="main-content">

    <!-- Título da seção principal -->
    <h1>Buscador de Livros</h1>

    <!-- Exibição de mensagens (como confirmações ou erros) -->
    <?php if ($msg): ?>
        <div class="message"><?= $msg ?></div>
    <?php endif; ?>

    <!-- Caixa de busca -->
    <div class="search-box">
        <!-- Campo de texto da busca -->
        <input id="query" placeholder="Título, autor ou ISBN" autocomplete="off" />
        
        <!-- Opções de filtro da busca -->
        <select id="searchType">
            <option value="title">Título</option>
            <option value="author">Autor</option>
            <option value="isbn">ISBN</option>
        </select>

        <!-- Botão que inicia a busca -->
        <button onclick="buscarLivro(true)">Buscar</button>
    </div>

    <!-- Área onde os resultados serão exibidos -->
    <div id="resultado" class="results"></div>

    <!-- Área de erro, se houver -->
    <div id="erro" class="error"></div>

    <!-- Botão para carregar mais resultados -->
    <button id="loadMoreBtn" style="display:none" onclick="buscarLivro(false)">Carregar mais</button>
</div>

<!-- Script JavaScript para integração com a API do Google Books e busca local -->
<script>
    let startIndex = 0;
    let currentQuery = '';
    let currentSearchType = '';
    const maxResults = 40;

    async function buscarLivro(nova) {
        const qEl = document.getElementById('query'); // Campo de texto
        const tEl = document.getElementById('searchType'); // Tipo de busca
        const resEl = document.getElementById('resultado'); // Onde exibe os livros
        const errEl = document.getElementById('erro'); // Área de erros
        const btn = document.getElementById('loadMoreBtn'); // Botão "Carregar mais"

        if (nova) {
            startIndex = 0;
            currentQuery = qEl.value.trim();
            currentSearchType = tEl.value;
            resEl.innerHTML = '';
            btn.style.display = 'none';
        }

        errEl.innerText = '';
        if (!currentQuery) {
            errEl.innerText = 'Digite algo.';
            return;
        }

        let q = '';
        const val = currentQuery;

        if (currentSearchType === 'title') {
            q = val.split(/\s+/).map(p => 'intitle:' + p).join('+');
        } else if (currentSearchType === 'author') {
            q = 'inauthor:' + val;
        } else if (currentSearchType === 'isbn') {
            q = 'isbn:' + val.replace(/[\s\-]/g, '');
        }

        try {
            const res = await fetch(`?busca=${encodeURIComponent(q)}&startIndex=${startIndex}`);
            const data = await res.json();

            if (!data.items) {
                errEl.innerText = 'Nenhum livro encontrado.';
                return;
            }

            const exibidos = new Set();
            document.querySelectorAll('.book-card p strong').forEach(e => {
                if (e.innerText === 'ISBN:') {
                    exibidos.add(e.parentNode.innerText.replace('ISBN: ', '').trim());
                }
            });

            for (let item of data.items) {
                const v = item.volumeInfo;
                let isbn = 'ISBN não disponível';

                if (v.industryIdentifiers) {
                    const id13 = v.industryIdentifiers.find(i => i.type === 'ISBN_13');
                    const id10 = v.industryIdentifiers.find(i => i.type === 'ISBN_10');
                    isbn = id13 ? id13.identifier : (id10 ? id10.identifier : isbn);
                }

                if (exibidos.has(isbn)) continue;
                exibidos.add(isbn);

                const form = document.createElement('form');
                form.method = 'POST';

                ['titulo', 'isbn', 'autor'].forEach(f => {
                    const i = document.createElement('input');
                    i.type = 'hidden';
                    i.name = f;
                    if (f === 'titulo') i.value = v.title || '';
                    else if (f === 'isbn') i.value = isbn;
                    else i.value = (v.authors || []).join(', ');
                    form.appendChild(i);
                });

                const btn = document.createElement('button');
                btn.type = 'submit';
                btn.className = 'register-btn';
                btn.textContent = 'Registrar';
                form.appendChild(btn);

                const card = document.createElement('div');
                card.className = 'book-card';
                card.innerHTML = `
                    <img src="${v.imageLinks?.thumbnail || 'https://via.placeholder.com/128x180'}" alt="Capa do livro" />
                    <h3>${v.title || 'Sem título'}</h3>
                    <p><strong>Autor:</strong> ${(v.authors || ['?']).join(', ')}</p>
                    <p><strong>ISBN:</strong> ${isbn}</p>
                `;

                card.appendChild(form);
                resEl.appendChild(card);
            }

            startIndex += maxResults;
            btn.style.display = (data.totalItems > startIndex) ? 'block' : 'none';

            // Busca local no banco
            const locais = await (await fetch(`?busca_local=${encodeURIComponent(currentQuery)}&campo=${currentSearchType}`)).json();

            for (let livro of locais) {
                if (exibidos.has(livro.isbn)) continue;

                const card = document.createElement('div');
                card.className = 'book-card';
                card.innerHTML = `
                    <img src="${livro.imagem || 'https://via.placeholder.com/128x180'}" alt="Capa do livro" />
                    <h3>${livro.nome_livro}</h3>
                    <p><strong>Autor:</strong> ${livro.nome_autor}</p>
                    <p><strong>ISBN:</strong> ${livro.isbn}</p>
                `;

                resEl.appendChild(card);
                exibidos.add(livro.isbn);
            }
        } catch (e) {
            console.error(e);
            errEl.innerText = 'Erro ao buscar.';
        }
    }
</script>

</body>
</html>