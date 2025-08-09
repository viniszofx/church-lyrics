<?php
include('../conexao.php');

$id = $_GET['id'] ?? null;
$excluir = $_GET['excluir'] ?? null;

$igreja = '';
$sacerdote = '';
$dataInicio = '';
$dataFim = '';
$status = 1;

if ($excluir) {
    $stmt = $conn->prepare("DELETE FROM tbIgrejaSacerdote WHERE idIgrejaSacerdote = ?");
    $stmt->bind_param("i", $excluir);
    $stmt->execute();
    header("Location: vinculaigrejasacerdote_list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idIgrejaSacerdote = $_POST['idIgrejaSacerdote'] ?? null;
    $idIgreja = $_POST['idIgreja'];
    $idSacerdote = $_POST['idSacerdote'];
    $dataInicio = $_POST['DataInicio'];
    $dataFim = $_POST['DataFim'] ?: null;
    $status = $_POST['Status'];

    if ($idIgrejaSacerdote) {
        $stmt = $conn->prepare("UPDATE tbIgrejaSacerdote SET idIgreja = ?, idSacerdote = ?, DataInicio = ?, DataFim = ?, Status = ? WHERE idIgrejaSacerdote = ?");
        $stmt->bind_param("iissii", $idIgreja, $idSacerdote, $dataInicio, $dataFim, $status, $idIgrejaSacerdote);
    } else {
        $stmt = $conn->prepare("INSERT INTO tbIgrejaSacerdote (idIgreja, idSacerdote, DataInicio, DataFim, Status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissi", $idIgreja, $idSacerdote, $dataInicio, $dataFim, $status);
    }

    $stmt->execute();
    header("Location: vinculaigrejasacerdote_list.php");
    exit;
}

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM tbIgrejaSacerdote WHERE idIgrejaSacerdote = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    $igreja = $res['idIgreja'];
    $sacerdote = $res['idSacerdote'];
    $dataInicio = $res['DataInicio'];
    $dataFim = $res['DataFim'];
    $status = $res['Status'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Editar Vínculo' : 'Novo Vínculo' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <h3><?= $id ? 'Editar Vínculo Igreja x Sacerdote' : 'Novo Vínculo Igreja x Sacerdote' ?></h3>

    <form method="post">
        <input type="hidden" name="idIgrejaSacerdote" value="<?= $id ?>">

        <div class="mb-3">
            <label class="form-label">Igreja</label>
            <select name="idIgreja" class="form-select" required>
                <option value="">Selecione</option>
                <?php
                $res = $conn->query("SELECT idIgreja, NomeIgreja FROM tbIgreja");
                while ($row = $res->fetch_assoc()) {
                    $sel = ($igreja == $row['idIgreja']) ? 'selected' : '';
                    echo "<option value='{$row['idIgreja']}' $sel>{$row['NomeIgreja']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Sacerdote</label>
            <select name="idSacerdote" class="form-select" required>
                <option value="">Selecione</option>
                <?php
                $res = $conn->query("SELECT idSacerdote, NomeSacerdote FROM tbSacerdotes");
                while ($row = $res->fetch_assoc()) {
                    $sel = ($sacerdote == $row['idSacerdote']) ? 'selected' : '';
                    echo "<option value='{$row['idSacerdote']}' $sel>{$row['NomeSacerdote']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Data Início</label>
            <input type="date" name="DataInicio" class="form-control" value="<?= $dataInicio ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Data Fim</label>
            <input type="date" name="DataFim" class="form-control" value="<?= $dataFim ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="Status" class="form-select" required>
                <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Ativo</option>
                <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Inativo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="vinculaigrejasacerdote_list.php" class="btn btn-secondary">Cancelar</a>
    </form>

</body>
</html>
