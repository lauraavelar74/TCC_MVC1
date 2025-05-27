<?php
// conexão PDO
$host = 'localhost';
$db = 'biblioteca_mvc';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'], $_POST['isbn'], $_POST['autor'])) {
    $titulo = trim($_POST['titulo']);
    $isbn = trim($_POST['isbn']);
    $autor = trim($_POST['autor']);

    if ($titulo && $isbn && $autor) {
        $stmt = $pdo->prepare("INSERT INTO livros (titulo, isbn, autor) VALUES (:titulo, :isbn, :autor)");
        $stmt->execute([':titulo' => $titulo, ':isbn' => $isbn, ':autor' => $autor]);
        $msg = "Livro registrado com sucesso! Título: " . htmlspecialchars($titulo);
    } else {
        $msg = "Dados insuficientes para registrar o livro.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Busca e Registro de Livros</title>
<style>
/* Seu CSS já aqui... */
body {
    font-family: Arial, sans-serif;
    margin: 0; padding: 0;
    background-image: url("./img/hello2.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}
h1 { text-align:center; color:#333; }
.search-box {
    display:flex; justify-content:center; margin-bottom:20px; gap:10px;
}
input, select, button {
    padding:10px; font-size:16px; border:1px solid #aaa; border-radius:5px;
}
button {
    background-color:#0066cc; color:#fff; border:none; cursor:pointer;
}
button:hover { background-color:#004999; }
.results {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:20px;
    padding: 0 20px;
}
.book-card {
    background:#fff;
    border-radius:8px;
    padding:15px;
    box-shadow:0 0 8px rgba(0,0,0,0.1);
    display:flex; flex-direction:column;
}
.book-card img {
    max-width:100%;
    border-radius:5px;
}
.book-card h3 {
    margin-top:10px;
    font-size:18px;
}
.book-card p {
    margin:5px 0;
    font-size:14px;
}
.error {
    text-align:center;
    color:red;
    font-weight:bold;
}
.register-btn {
    margin-top:10px;
    padding:8px 12px;
    background-color:#28a745;
    color:#fff;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-size:16px;
}
.register-btn:hover { background-color:#1e7e34; }
.message {
    text-align:center;
    margin: 10px 0;
    color: green;
    font-weight: bold;
}
</style>
</head>
<body>

<h1>Buscador de Livros (Google Books API)</h1>

<?php if ($msg): ?>
    <div class="message"><?= $msg ?></div>
<?php endif; ?>

<div class="search-box">
    <input type="text" id="query" placeholder="Digite título, autor ou ISBN" />
    <select id="searchType">
        <option value="title">Título</option>
        <option value="author">Autor</option>
        <option value="isbn">ISBN</option>
    </select>
    <button onclick="buscarLivro()">Buscar</button>
</div>

<div id="resultado" class="results"></div>
<div id="erro" class="error"></div>

<script>
async function buscarLivro() {
    const query = document.getElementById('query').value.trim();
    const searchType = document.getElementById('searchType').value;
    const resultado = document.getElementById('resultado');
    const erro = document.getElementById('erro');

    resultado.innerHTML = '';
    erro.innerText = '';

    if (!query) {
        erro.innerText = 'Por favor, insira uma consulta.';
        return;
    }

    let q = '';
    if (searchType === 'title') q = 'intitle:' + query;
    else if (searchType === 'author') q = 'inauthor:' + query;
    else if (searchType === 'isbn') q = 'isbn:' + query;
    else {
        erro.innerText = 'Tipo de pesquisa inválido.';
        return;
    }

    const url = `https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(q)}`;

    try {
        const res = await fetch(url);
        const data = await res.json();

        if (!data.items || data.items.length === 0) {
            erro.innerText = 'Nenhum livro encontrado.';
            return;
        }

        const exibidos = new Set();

        data.items.forEach(item => {
            const v = item.volumeInfo;
            let isbn = 'ISBN não disponível';

            if (v.industryIdentifiers) {
                const id13 = v.industryIdentifiers.find(i => i.type === 'ISBN_13');
                const id10 = v.industryIdentifiers.find(i => i.type === 'ISBN_10');
                isbn = id13 ? id13.identifier : (id10 ? id10.identifier : isbn);
            }

            if (isbn === 'ISBN não disponível' || exibidos.has(isbn)) return;
            exibidos.add(isbn);

 // Criar formulário para enviar via POST o livro para registrar
const form = document.createElement('form');
form.method = 'POST';
form.action = 'registrar_livro.php'; // <-- CORRIGIDO: agora envia para registrar_livro.php
form.style.marginTop = '10px';

const inputTitle = document.createElement('input');
inputTitle.type = 'hidden';
inputTitle.name = 'titulo';  // nomes iguais aos que o PHP espera
inputTitle.value = v.title || '';

const inputIsbn = document.createElement('input');
inputIsbn.type = 'hidden';
inputIsbn.name = 'isbn';
inputIsbn.value = isbn;

const inputAuthor = document.createElement('input');
inputAuthor.type = 'hidden';
inputAuthor.name = 'autor';
inputAuthor.value = v.authors ? v.authors.join(', ') : '';

form.appendChild(inputTitle);
form.appendChild(inputAuthor);
form.appendChild(inputIsbn);

const btn = document.createElement('button');
btn.type = 'submit';
btn.className = 'register-btn';
btn.innerText = 'Registrar Livro';

form.appendChild(btn);




            const card = document.createElement('div');
            card.className = 'book-card';
            card.innerHTML = `
                <img src="${v.imageLinks?.thumbnail || 'https://via.placeholder.com/128x180?text=Sem+Imagem'}" />
                <h3>${v.title || 'Título não disponível'}</h3>
                <p><strong>Autor:</strong> ${v.authors ? v.authors.join(', ') : 'Autor não disponível'}</p>
                <p><strong>ISBN:</strong> ${isbn}</p>
            `;
            card.appendChild(form);

            resultado.appendChild(card);
        });
    } catch (err) {
        erro.innerText = 'Erro ao buscar livros.';
        console.error(err);
    }
}
</script>

</body>
</html>

