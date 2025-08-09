<?php
include('../conexao.php');

if (!isset($_GET['id'])) {
    echo "ID da música não especificado.";
    exit;
}

$idMusica = intval($_GET['id']);

// Buscar dados da música
$sql = "SELECT * FROM tbmusica WHERE idMusica = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idMusica);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Música não encontrada.";
    exit;
}

$row = $result->fetch_assoc();

// Buscar todos os momentos disponíveis
$momentos = [];
$sqlMomentos = "SELECT * FROM tbmomentosmissa ORDER BY DescMomento ASC";
$resultMomentos = $conn->query($sqlMomentos);
while ($m = $resultMomentos->fetch_assoc()) {
    $momentos[] = $m;
}

// Buscar momentos já vinculados à música
$momentosMarcados = [];
$sqlVinculos = "SELECT idMomento FROM tbmusicamomentomissa WHERE idMusica = ?";
$stmtVinculo = $conn->prepare($sqlVinculos);
$stmtVinculo->bind_param("i", $idMusica);
$stmtVinculo->execute();
$resVinculo = $stmtVinculo->get_result();
while ($v = $resVinculo->fetch_assoc()) {
    $momentosMarcados[] = $v['idMomento'];
}

// Buscar todos os tempos litúrgicos
$tempos = [];
$sqlTempos = "SELECT * FROM tbtpliturgico ORDER BY DescTempo ASC";
$resultTempos = $conn->query($sqlTempos);
while ($t = $resultTempos->fetch_assoc()) {
    $tempos[] = $t;
}

// Buscar tempos litúrgicos já vinculados à música
$temposMarcados = [];
$sqlTempoVinculo = "SELECT idTpLiturgico FROM tbTempoMusica WHERE idMusica = ?";
$stmtTempo = $conn->prepare($sqlTempoVinculo);
$stmtTempo->bind_param("i", $idMusica);
$stmtTempo->execute();
$resTempo = $stmtTempo->get_result();
while ($tv = $resTempo->fetch_assoc()) {
    $temposMarcados[] = $tv['idTpLiturgico'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Música</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
</head>
<body class="container mt-4">
    <h3 class="mb-4">Editar Música</h3>
    <form action="musicas_salvar.php" method="post">
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
            <a href="musicas.php" class="btn btn-secondary">Sair</a>
            <a href="musicas.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</body>
</html>
