<?php
include_once('../conexao.php');
if (!isset($_GET['id'])) {
    header("Location: momentosmissa.php");
    exit;
}
$id = $_GET['id'];
$res = $conn->query("SELECT * FROM tbmomentosmissa WHERE idMomento = $id");
if ($res->num_rows == 0) {
    echo "<div class='alert alert-danger'>Registro não encontrado.</div>";
    exit;
}
$row = $res->fetch_assoc();

if (isset($_POST['salvar'])) {
    $desc = $_POST['DescMomento'];
    $ordem = $_POST['OrdemDeExecucao'];
    $conn->query("UPDATE tbmomentosmissa SET DescMomento='$desc', OrdemDeExecucao='$ordem' WHERE idMomento = $id");
    header("Location: momentosmissa.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Momento da Missa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Editar Momento da Missa</h2>
    <a href="momentosmissa.php" class="btn btn-secondary btn-sm mb-3">Voltar à página anterior</a>
    <form method="post" class="border p-3">
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <input type="text" name="DescMomento" class="form-control" value="<?= $row['DescMomento'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ordem de Execução</label>
            <input type="text" name="OrdemDeExecucao" class="form-control" value="<?= $row['OrdemDeExecucao'] ?>" required>
        </div>
        <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
        <a href="momentosmissa.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
