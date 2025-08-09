<?php include_once('../conexao.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Datas Comemorativas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Datas Comemorativas</h2>
    <a href="index.php" class="btn btn-secondary btn-sm mb-3">Voltar</a>

    <form method="post" class="border p-3 mb-4">
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <input type="text" name="DescComemoracao" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quando Acontece</label>
            <input type="text" name="QuandoAcontece" class="form-control" required>
        </div>
        <button type="submit" name="inserir" class="btn btn-primary">Salvar</button>
    </form>

    <?php
    if (isset($_POST['inserir'])) {
        $desc = $_POST['DescComemoracao'];
        $quando = $_POST['QuandoAcontece'];
        $conn->query("INSERT INTO tbdatacomemorativa (DescComemoracao, QuandoAcontece) VALUES ('$desc', '$quando')");
        echo '<div class="alert alert-success">Data cadastrada!</div>';
    }

    if (isset($_GET['excluir'])) {
        $id = $_GET['excluir'];
        $conn->query("DELETE FROM tbdatacomemorativa WHERE idDataComemorativa = $id");
        echo '<div class="alert alert-danger">Data excluída!</div>';
    }
    ?>

    <table class="table table-bordered table-striped">
        <thead><tr><th>ID</th><th>Descrição</th><th>Quando</th><th>Ações</th></tr></thead>
        <tbody>
        <?php
        $res = $conn->query("SELECT * FROM tbdatacomemorativa ORDER BY idDataComemorativa DESC");
        while ($row = $res->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['idDataComemorativa'] ?></td>
            <td><?= $row['DescComemoracao'] ?></td>
            <td><?= $row['QuandoAcontece'] ?></td>
            <td>
                <a href="?excluir=<?= $row['idDataComemorativa'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir esta data?')">Excluir</a>
                <a href="datacomemorativa_editar.php?id=<?= $row['idDataComemorativa'] ?>" class="btn btn-sm btn-warning">Editar</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<!-- datacomemorativa.php placeholder -->