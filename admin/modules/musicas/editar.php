<?php
include_once("../../includes/header.php");

if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">ID da música não especificado.</div>';
    include_once("../../includes/footer.php");
    exit;
}

$idMusica = intval($_GET['id']);

// Buscar dados da música
$sql = "SELECT * FROM tbmusica WHERE idMusica = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo '<div class="alert alert-danger">Erro ao preparar consulta da música: ' . $conn->error . '</div>';
    include_once("../../includes/footer.php");
    exit;
}
$stmt->bind_param("i", $idMusica);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<div class="alert alert-danger">Música não encontrada.</div>';
    include_once("../../includes/footer.php");
    exit;
}

$row = $result->fetch_assoc();

// Buscar todos os momentos disponíveis
$momentos = [];
$sqlMomentos = "SELECT * FROM tbmomentosmissa ORDER BY DescMomento ASC";
$resultMomentos = $conn->query($sqlMomentos);
if (!$resultMomentos) {
    echo '<div class="alert alert-danger">Erro ao buscar momentos: ' . $conn->error . '</div>';
} else {
    while ($m = $resultMomentos->fetch_assoc()) {
        $momentos[] = $m;
    }
}

// Buscar momentos já vinculados à música
$momentosMarcados = [];
$sqlVinculos = "SELECT idMomento FROM tbmusicamomentomissa WHERE idMusica = ?";
$stmtVinculo = $conn->prepare($sqlVinculos);
if (!$stmtVinculo) {
    echo '<div class="alert alert-danger">Erro ao preparar consulta de vínculos: ' . $conn->error . '</div>';
} else {
    $stmtVinculo->bind_param("i", $idMusica);
    $stmtVinculo->execute();
    $resVinculo = $stmtVinculo->get_result();
    while ($v = $resVinculo->fetch_assoc()) {
        $momentosMarcados[] = $v['idMomento'];
    }
}

// Buscar todos os tempos litúrgicos
$tempos = [];
$sqlTempos = "SELECT * FROM tbtpliturgico ORDER BY DescTempo ASC";
$resultTempos = $conn->query($sqlTempos);
if (!$resultTempos) {
    echo '<div class="alert alert-danger">Erro ao buscar tempos litúrgicos: ' . $conn->error . '</div>';
} else {
    while ($t = $resultTempos->fetch_assoc()) {
        $tempos[] = $t;
    }
}

// Buscar tempos litúrgicos já vinculados à música
$temposMarcados = [];
$sqlTempoVinculo = "SELECT idTpLiturgico FROM tbtempomusica WHERE idMusica = ?";
$stmtTempo = $conn->prepare($sqlTempoVinculo);
if (!$stmtTempo) {
    echo '<div class="alert alert-danger">Erro ao preparar consulta de tempos: ' . $conn->error . '</div>';
} else {
    $stmtTempo->bind_param("i", $idMusica);
    $stmtTempo->execute();
    $resTempo = $stmtTempo->get_result();
    while ($tv = $resTempo->fetch_assoc()) {
        $temposMarcados[] = $tv['idTpLiturgico'];
    }
}
?>

<style>
    .checkbox-group {
        border: 1px solid #dee2e6;
        padding: 10px;
        border-radius: 8px;
        max-height: 200px;
        overflow-y: auto;
        background-color: #f8f9fa;
    }
    .form-label {
        font-weight: bold;
        margin-top: 10px;
    }
</style>

<h3 class="mb-4">Editar Música</h3>
<form action="salvar.php" method="post">
    <input type="hidden" name="idMusica" value="<?= $idMusica ?>">

    <div class="mb-3">
        <label class="form-label">Nome da Música:</label>
        <input type="text" name="NomeMusica" class="form-control" value="<?= htmlspecialchars($row['NomeMusica']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Música:</label>
        <textarea name="Musica" rows="6" class="form-control" required><?= htmlspecialchars($row['Musica']) ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Momentos da Missa:</label>
        <div class="checkbox-group">
            <?php foreach ($momentos as $momento): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="momentos[]" value="<?= $momento['idMomento'] ?>"
                        <?= in_array($momento['idMomento'], $momentosMarcados) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= htmlspecialchars($momento['DescMomento']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Tempos Litúrgicos:</label>
        <div class="checkbox-group">
            <?php foreach ($tempos as $tempo): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tempos[]" value="<?= $tempo['idTpLiturgico'] ?>"
                        <?= in_array($tempo['idTpLiturgico'], $temposMarcados) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= htmlspecialchars($tempo['DescTempo']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="list.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php include_once("../../includes/footer.php"); ?>
