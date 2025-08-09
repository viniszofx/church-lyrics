<?php
include('../conexao.php');
$idIgreja = (int)$_GET['idIgreja'];
$idSacerdote = (int)$_GET['idSacerdote'];

$sql = "SELECT * FROM tbIgrejaSacerdote WHERE idIgreja = ? AND idSacerdote = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idIgreja, $idSacerdote);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Vínculo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Editar Vínculo</h2>
        <a href="vinculaigrejasacerdote_list.php" class="btn btn-secondary">Voltar à Tela Anterior</a>
    </div>

    <form action="vinculaigrejasacerdote_salvar.php" method="post">
        <input type="hidden" name="idIgreja" value="<?= $idIgreja ?>">
        <input type="hidden" name="idSacerdote" value="<?= $idSacerdote ?>">

        <div class="mb-3">
            <label class="form-label">Igreja</label>
            <input type="text" class="form-control" value="<?=
                $conn->query("SELECT NomeIgreja FROM tbIgreja WHERE idIgreja = $idIgreja")->fetch_assoc()['NomeIgreja'];
            ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Sacerdote</label>
            <input type="text" class="form-control" value="<?=
                $conn->query("SELECT NomeSacerdote FROM tbSacerdote WHERE idSacerdote = $idSacerdote")->fetch_assoc()['NomeSacerdote'];
            ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Data de Início</label>
            <input type="date" name="DataInicio" value="<?= $row['DataInicio'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Data de Fim</label>
            <input type="date" name="DataFim" value="<?= $row['DataFim'] ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="Status" class="form-select">
                <option value="1" <?= $row['Status'] ? 'selected' : '' ?>>Ativo</option>
                <option value="0" <?= !$row['Status'] ? 'selected' : '' ?>>Inativo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="vinculaigrejasacerdote_list.php" class="btn btn-danger">Cancelar</a>
    </form>
</body>
</html>
