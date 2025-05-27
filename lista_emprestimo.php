<?php
// listar_emprestimos.php - Exibir todos os empréstimos realizados

include('config.php');

$sql = "SELECT e.id, p.nome AS professor, a.nome AS aluno, l.nome_livro, e.data_emprestimo, e.data_devolucao
        FROM emprestimo e
        JOIN professor p ON e.id_professor = p.id
        JOIN alunos a ON e.id_aluno = a.id
        JOIN livro l ON e.id_livro = l.id";
$stmt = $pdo->query($sql);

echo "<table border='1'>
        <tr>
            <th>Professor</th>
            <th>Aluno</th>
            <th>Livro</th>
            <th>Data de Empréstimo</th>
            <th>Data de Devolução</th>
        </tr>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
            <td>" . $row['professor'] . "</td>
            <td>" . $row['aluno'] . "</td>
            <td>" . $row['nome_livro'] . "</td>
            <td>" . $row['data_emprestimo'] . "</td>
            <td>" . $row['data_devolucao'] . "</td>
          </tr>";
}

echo "</table>";
?>
