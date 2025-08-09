<?php
include_once('../conexao.php');
if (!isset($_GET['id'])) {
    header("Location: tpliturgico.php");
    exit;
}
$id = $_GET['id'];
$res = $conn->query("SELECT * FROM tbtpliturgico WHERE idTpLiturgico = $id");
if ($res->num_rows == 0) {
    echo "<div class='alert alert-danger'>Registro não encontrado.</div>";
    exit;
}
$row = $res->fetch_assoc();

if (isset($_POST['salvar'])) {
    $desc = $_POST['DescTempo'];
    $sigla = $_POST['Sigla'];
    $conn->query("UPDATE tbtpliturgico SET DescTempo='$desc', Sigla='$sigla' WHERE idTpLiturgico = $id");
    header("Location: tpliturgico.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Tempo Litúrgico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Editar Tempo Litúrgico</h2>
    <form method="post" class="border p-3">
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <input type="text" name="DescTempo" class="form-control" value="<?= $row['DescTempo'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Sigla</label>
            <input type="text" name="Sigla" class="form-control" maxlength="2" value="<?= $row['Sigla'] ?>" required>
        </div>
        <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
        <a href="tpliturgico.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
