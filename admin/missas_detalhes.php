<?php
include_once("../conexao.php");

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID da missa não informado.</div>";
    exit;
}

$idMissa = (int) $_GET['id'];

// Buscar dados da missa
$sqlMissa = "
SELECT m.*, i.NomeIgreja, dc.DescComemoracao
FROM tbmissa m
LEFT JOIN tbigreja i ON i.idIgreja = m.idIgreja
LEFT JOIN tbdatacomemorativa dc ON dc.idDataComemorativa = m.idDataComemorativa
WHERE m.idMissa = $idMissa";
$missa = $conn->query($sqlMissa)->fetch_assoc();

// Buscar músicas agrupadas por momento
$sqlMusicas = "
SELECT mo.idMomento, mu.NomeMusica, mo.DescMomento, mo.OrdemDeExecucao
FROM tbmissamusicas mmis
JOIN tbmusica mu ON mu.idMusica = mmis.idMusica
JOIN tbmomentosmissa mo ON mo.idMomento = mmis.idMusicaMomentoMissa
WHERE mmis.idMissa = $idMissa
ORDER BY mo.OrdemDeExecucao";
$musicas = $conn->query($sqlMusicas)->fetch_all(MYSQLI_ASSOC);

// Agrupar por momento
$agrupadas = [];
foreach ($musicas as $m) {
    $momento = $m['DescMomento'];
    $agrupadas[$momento][] = $m['NomeMusica'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Missa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="mb-3">📖 Detalhes da Missa</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($missa['DataMissa'])) ?></p>
            <p><strong>Ano:</strong> <?= htmlspecialchars($missa['AnoMissa']) ?></p>
            <p><strong>Título:</strong> <?= htmlspecialchars($missa['TituloMissa']) ?></p>
            <p><strong>Status:</strong> <?= $missa['Status'] ? 'Ativa' : 'Inativa' ?></p>
            <p><strong>Igreja:</strong> <?= htmlspecialchars($missa['NomeIgreja'] ?? '-') ?></p>
            <p><strong>Comemoração:</strong> <?= htmlspecialchars($missa['DescComemoracao'] ?? '-') ?></p>
        </div>
    </div>

    <h5>Músicas da Missa</h5>
    <?php if ($agrupadas): ?>
        <?php foreach ($agrupadas as $momento => $lista): ?>
            <div class="mb-3">
                <strong><?= $momento ?></strong>
                <ul class="list-group">
                    <?php foreach ($lista as $musica): ?>
                        <li class="list-group-item"><?= htmlspecialchars($musica) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">Nenhuma música vinculada.</p>
    <?php endif; ?>

    <a href="missas.php" class="btn btn-secondary mt-4">← Voltar</a>
</div>
</body>
</html>
