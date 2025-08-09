<?php include_once('../conexao.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Momentos da Missa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Momentos da Missa</h2>
    <a href="index.php" class="btn btn-secondary btn-sm mb-3">Voltar ao menu principal</a>

    <form method="post" class="border p-3 mb-4">
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <input type="text" name="DescMomento" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ordem de Execução</label>
            <input type="text" name="OrdemDeExecucao" class="form-control" required>
        </div>
        <button type="submit" name="inserir" class="btn btn-primary">Salvar</button>
    </form>

    <?php
    if (isset($_POST['inserir'])) {
        $desc = $_POST['DescMomento'];
        $ordem = $_POST['OrdemDeExecucao'];
        $conn->query("INSERT INTO tbmomentosmissa (DescMomento, OrdemDeExecucao) VALUES ('$desc', '$ordem')");
        echo '<div class="alert alert-success">Momento cadastrado!</div>';
    }

    if (isset($_GET['excluir'])) {
        $id = $_GET['excluir'];
        $conn->query("DELETE FROM tbmomentosmissa WHERE idMomento = $id");
        echo '<div class="alert alert-danger">Momento excluído!</div>';
    }
    ?>

    <table class="table table-bordered table-striped">
        <thead><tr><th>ID</th><th>Descrição</th><th>Ordem</th><th>Ações</th></tr></thead>
        <tbody>
        <?php
        $res = $conn->query("SELECT * FROM tbmomentosmissa ORDER BY OrdemDeExecucao ASC");
        while ($row = $res->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['idMomento'] ?></td>
            <td><?= $row['DescMomento'] ?></td>
            <td><?= $row['OrdemDeExecucao'] ?></td>
            <td>
                <a href="?excluir=<?= $row['idMomento'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este momento?')">Excluir</a>
                <a href="momentosmissa_editar.php?id=<?= $row['idMomento'] ?>" class="btn btn-sm btn-warning">Editar</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<!-- momentosmissa.php placeholder -->