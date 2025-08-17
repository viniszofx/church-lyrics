<?php
include_once('../conexao.php');

// Inserção
if (isset($_POST['inserir'])) {
    $nome = $_POST['DescComemoracao'];
    $quando = $_POST['QuandoAcontece'];
    $mesdia = $_POST['MesDia'];
    $conn->query("INSERT INTO tbdatacomemorativa (DescComemoracao, QuandoAcontece, MesDia) VALUES ('$nome', '$quando', '$mesdia')");
    echo "<div class='alert alert-success'>Data comemorativa adicionada!</div>";
}

// Exclusão
if (isset($_GET['excluir'])) {
    $id = (int) $_GET['excluir'];
    $conn->query("DELETE FROM tbdatacomemorativa WHERE idDataComemorativa = $id");
    echo "<div class='alert alert-warning'>Data comemorativa removida.</div>";
}

// Consulta
$query = "SELECT * FROM tbdatacomemorativa ORDER BY QuandoAcontece DESC";
$datas = $conn->query($query);

if ($datas === false) {
    echo "<div class='alert alert-danger'>Erro na consulta: " . $conn->error . "<br>Query: " . $query . "</div>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Datas Comemorativas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Datas Comemorativas</h2>
    <a href="index.php" class="btn btn-secondary btn-sm mb-3">⬅️ Voltar ao menu principal</a>
    <form method="post" class="border p-3 mb-4 bg-white rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label">Nome Comemoração</label>
            <input type="text" name="DescComemoracao" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quando Acontece</label>
            <input type="text" name="QuandoAcontece" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mes/dia</label>
            <input type="text" name="MesDia" class="form-control" required>
        </div>
        <button type="submit" name="inserir" class="btn btn-primary">Salvar</button>
    </form>

    <h5 class="mb-3">Listagem</h5>
    <table class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Quando</th>
                <th>MesDia</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        if ($datas && $datas->num_rows > 0) {
            while ($d = $datas->fetch_assoc()): ?>
            <tr>
                <td><?= $d['idDataComemorativa'] ?></td>
                <td><?= htmlspecialchars($d['DescComemoracao']) ?></td>
                <td><?= htmlspecialchars($d['QuandoAcontece']) ?></td>
                <td><?= htmlspecialchars($d['MesDia']) ?></td>
                <td>
                    <a href="?excluir=<?= $d['idDataComemorativa'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir esta data?')">Excluir</a>
                    <a href="datacomemorativa_editar.php?id=<?= $d['idDataComemorativa'] ?>" class="btn btn-sm btn-warning">Editar</a>
                 </td>
            </tr>
        <?php endwhile; 
        } else {
            echo "<tr><td colspan='5' class='text-center'>Nenhuma data comemorativa encontrada</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
