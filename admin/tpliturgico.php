<?php include_once('../conexao.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Tempos Litúrgicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Cadastro - Tempos Litúrgicos</h2>
    <a href="index.php" class="btn btn-secondary btn-sm mb-3">⬅️ Voltar ao menu principal</a>

    <!-- Formulário de inserção -->
    <form method="post" class="border p-3 mb-4" action="">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Descrição do Tempo</label>
                <input type="text" name="DescTempo" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Sigla</label>
                <input type="text" name="Sigla" class="form-control" maxlength="2" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" name="inserir" class="btn btn-primary">Cadastrar</button>
            </div>
        </div>
    </form>

    <?php
    // Inserção
    if (isset($_POST['inserir'])) {
        $desc = $_POST['DescTempo'];
        $sigla = $_POST['Sigla'];
        $conn->query("INSERT INTO tbtpliturgico (DescTempo, Sigla) VALUES ('$desc', '$sigla')");
        echo '<div class="alert alert-success">Registro inserido com sucesso!</div>';
    }

    // Exclusão
    if (isset($_GET['excluir'])) {
        $id = $_GET['excluir'];
        $conn->query("DELETE FROM tbtpliturgico WHERE idTpLiturgico = $id");
        echo '<div class="alert alert-danger">Registro excluído!</div>';
    }
    ?>

    <!-- Tabela de listagem -->
    <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Descrição</th><th>Sigla</th><th>Ações</th></tr></thead>
        <tbody>
        <?php
        $res = $conn->query("SELECT * FROM tbtpliturgico ORDER BY idTpLiturgico ASC");
        while ($row = $res->fetch_assoc()):
        ?>
            <tr>
                <td><?= $row['idTpLiturgico'] ?></td>
                <td><?= $row['DescTempo'] ?></td>
                <td><?= $row['Sigla'] ?></td>
                <td>
                    <a href="?excluir=<?= $row['idTpLiturgico'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmar exclusão?')">Excluir</a>
                    <a href="tpliturgico_editar.php?id=<?= $row['idTpLiturgico'] ?>" class="btn btn-sm btn-warning">Editar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<!-- tpliturgico.php placeholder -->