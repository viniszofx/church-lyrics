<?php
include_once('../conexao.php');

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID da data comemorativa não informado.</div>";
    exit;
}

$id = (int) $_GET['id'];

// Buscar o registro
$res = $conn->query("SELECT * FROM tbDataComemorativa WHERE idDataComemorativa = $id");
if ($res->num_rows === 0) {
    echo "<div class='alert alert-warning'>Registro não encontrado.</div>";
    exit;
}
$row = $res->fetch_assoc();

// Atualizar
if (isset($_POST['salvar'])) {
    $nome = $_POST['NomeDataComemorativa'];
    $data = $_POST['DataComemorativa'];
    $conn->query("UPDATE tbDataComemorativa SET NomeDataComemorativa='$nome', DataComemorativa='$data' WHERE idDataComemorativa = $id");
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

    <form method="post" class="border p-3 bg-white rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label">Nome da Data</label>
            <input type="text" name="NomeDataComemorativa" class="form-control" value="<?= htmlspecialchars($row['NomeDataComemorativa']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Data</label>
            <input type="date" name="DataComemorativa" class="form-control" value="<?= $row['DataComemorativa'] ?>" required>
        </div>
        <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
        <a href="datacomemorativa_geral.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
