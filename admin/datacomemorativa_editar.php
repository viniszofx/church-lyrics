<?php
include_once('../conexao.php');
if (!isset($_GET['id'])) {
    header("Location: datacomemorativa_geral.php");
    exit;
}
$id = $_GET['id'];
$res = $conn->query("SELECT * FROM tbdatacomemorativa WHERE idDataComemorativa = $id");
if ($res->num_rows == 0) {
    echo "<div class='alert alert-danger'>Registro não encontrado.</div>";
    exit;
}
$row = $res->fetch_assoc();

if (isset($_POST['salvar'])) {
    $desc = $_POST['DescComemoracao'];
    $quando = $_POST['QuandoAcontece'];
    $mesdia = $_POST['MesDia'];
    $conn->query("UPDATE tbdatacomemorativa SET DescComemoracao='$desc', QuandoAcontece='$quando' , MesDia='$mesdia' WHERE idDataComemorativa = $id");
    header("Location: datacomemorativa_geral.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Data Comemorativa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Editar Data Comemorativa</h2>
    <a href="datacomemorativa_geral.php" class="btn btn-secondary btn-sm mb-3">Voltar à página anterior</a>
    <form method="post" class="border p-3">
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <input type="text" name="DescComemoracao" class="form-control" value="<?= $row['DescComemoracao'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quando Acontece</label>
            <input type="text" name="QuandoAcontece" class="form-control" value="<?= $row['QuandoAcontece'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mes/Dia</label>
            <input type="text" name="MesDia" class="form-control" value="<?= $row['MesDia'] ?>" required>
        </div>
        <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
        <a href="datacomemorativa_geral.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
