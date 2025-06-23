<?php
$pdo = new PDO("mysql:host=localhost;dbname=biblioteca_mvc;charset=utf8mb4", 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'], $_POST['isbn'], $_POST['autor'])) {
    $t = trim($_POST['titulo']); $i = trim($_POST['isbn']); $a = trim($_POST['autor']);
    if ($t && $i && $a) {
        $c = $pdo->prepare("SELECT COUNT(*) FROM livros WHERE isbn = :isbn"); $c->execute([':isbn' => $i]);
        if (!$c->fetchColumn()) {
            $s = $pdo->prepare("INSERT INTO livros (nome_livro, isbn, nome_autor) VALUES (:t, :i, :a)");
            $s->execute([':t' => $t, ':i' => $i, ':a' => $a]); $msg = "Livro registrado: " . htmlspecialchars($t);
        } else $msg = "Livro com esse ISBN já existe.";
    } else $msg = "Dados insuficientes.";
}
if (isset($_GET['busca'])) {
    echo file_get_contents("https://www.googleapis.com/books/v1/volumes?q=" . urlencode($_GET['busca']) . "&startIndex=" . ($_GET['startIndex'] ?? 0) . "&maxResults=40"); exit;
}
if (isset($_GET['busca_local'])) {
    $q = trim($_GET['busca_local']); $campo = $_GET['campo'] ?? 'titulo';
    if (!$q) echo json_encode([]); else {
        $map = ['titulo' => 'nome_livro', 'autor' => 'nome_autor', 'isbn' => 'isbn'];
        $col = $map[$campo] ?? 'nome_livro'; $sql = $campo === 'isbn' ? "$col = :v" : "LOWER($col) LIKE LOWER(:v)";
        $v = $campo === 'isbn' ? $q : "%$q%"; $r = $pdo->prepare("SELECT * FROM livros WHERE $sql"); $r->execute([':v' => $v]);
        header('Content-Type: application/json'); echo json_encode($r->fetchAll()); exit;
    }
}
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Livros</title>
<style>body{font-family:sans-serif;background:#f0f0f0}h1{text-align:center}.search-box{display:flex;justify-content:center;gap:10px}input,select,button{padding:10px}button{background:#06c;color:#fff}button:hover{background:#049}.results{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;padding:0 20px}.book-card{background:#fff;padding:15px;text-align:center}.register-btn{margin-top:10px;background:#28a745;color:#fff}.message{text-align:center;color:green}.error{text-align:center;color:red}</style></head><body>
<h1>Buscador de Livros</h1>
<?php if ($msg): ?><div class="message"><?= $msg ?></div><?php endif; ?>
<div class="search-box">
<input id="query" placeholder="Título, autor ou ISBN">
<select id="searchType"><option value="title">Título</option><option value="author">Autor</option><option value="isbn">ISBN</option></select>
<button onclick="buscarLivro(true)">Buscar</button></div>
<div id="resultado" class="results"></div>
<div id="erro" class="error"></div>
<button id="loadMoreBtn" style="display:none" onclick="buscarLivro(false)">Carregar mais</button>
<script>
let startIndex = 0, currentQuery = '', currentSearchType = '', maxResults = 40;
async function buscarLivro(nova) {
    const qEl = document.getElementById('query'), tEl = document.getElementById('searchType');
    const resEl = document.getElementById('resultado'), errEl = document.getElementById('erro'), btn = document.getElementById('loadMoreBtn');
    if (nova) { startIndex = 0; currentQuery = qEl.value.trim(); currentSearchType = tEl.value; resEl.innerHTML = ''; btn.style.display = 'none'; }
    errEl.innerText = ''; if (!currentQuery) return errEl.innerText = 'Digite algo.';
    let q = ''; const val = currentQuery;
    if (currentSearchType === 'title') q = val.split(/\s+/).map(p => 'intitle:' + p).join('+');
    else if (currentSearchType === 'author') q = 'inauthor:' + val;
    else if (currentSearchType === 'isbn') q = 'isbn:' + val.replace(/[\s\-]/g, '');
    try {
        const res = await fetch(`?busca=${encodeURIComponent(q)}&startIndex=${startIndex}`);
        const data = await res.json(); if (!data.items) return errEl.innerText = 'Nenhum livro encontrado.';
        const exibidos = new Set(); document.querySelectorAll('.book-card p strong').forEach(e => {
            if (e.innerText === 'ISBN:') exibidos.add(e.parentNode.innerText.replace('ISBN: ', '').trim());
        });
        for (let item of data.items) {
            const v = item.volumeInfo;
            let isbn = 'ISBN não disponível';
            if (v.industryIdentifiers) {
                const id13 = v.industryIdentifiers.find(i => i.type === 'ISBN_13');
                const id10 = v.industryIdentifiers.find(i => i.type === 'ISBN_10');
                isbn = id13 ? id13.identifier : (id10 ? id10.identifier : isbn);
            }
            if (exibidos.has(isbn)) continue; exibidos.add(isbn);
            const form = document.createElement('form'); form.method = 'POST';
            ['titulo','isbn','autor'].forEach(f=>{const i=document.createElement('input');i.type='hidden';i.name=f;i.value=f==='titulo'?v.title||'':f==='isbn'?isbn:(v.authors||[]).join(', ');form.appendChild(i);});
            const btn = document.createElement('button'); btn.type = 'submit'; btn.className = 'register-btn'; btn.textContent = 'Registrar'; form.appendChild(btn);
            const card = document.createElement('div'); card.className = 'book-card';
            card.innerHTML = `<img src="${v.imageLinks?.thumbnail || 'https://via.placeholder.com/128x180'}"><h3>${v.title || 'Sem título'}</h3><p><strong>Autor:</strong> ${(v.authors || ['?']).join(', ')}</p><p><strong>ISBN:</strong> ${isbn}</p>`;
            card.appendChild(form); resEl.appendChild(card);
        }
        startIndex += maxResults; btn.style.display = (data.totalItems > startIndex) ? 'block' : 'none';
        const locais = await (await fetch(`?busca_local=${encodeURIComponent(currentQuery)}&campo=${currentSearchType}`)).json();
        for (let livro of locais) {
            if (exibidos.has(livro.isbn)) continue;
            const card = document.createElement('div'); card.className = 'book-card';
            card.innerHTML = `<img src="${livro.imagem || 'https://via.placeholder.com/128x180'}"><h3>${livro.nome_livro}</h3><p><strong>Autor:</strong> ${livro.nome_autor}</p><p><strong>ISBN:</strong> ${livro.isbn}</p>`;
            resEl.appendChild(card); exibidos.add(livro.isbn);
        }
    } catch (e) { console.error(e); errEl.innerText = 'Erro ao buscar.'; }
}
</script>
</body></html>
