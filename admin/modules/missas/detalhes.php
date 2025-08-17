<?php
include_once('../../includes/header.php');

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID da missa não informado.</div>";
    include_once('../../includes/footer.php');
    exit;
}

$idMissa = (int) $_GET['id'];

// Buscar dados da missa
$sqlMissa = "
    SELECT m.*, i.NomeIgreja, dc.DescComemoracao
    FROM tbmissa m
    LEFT JOIN tbigreja i ON i.idIgreja = m.idIgreja
    LEFT JOIN tbdatacomemorativa dc ON dc.idDataComemorativa = m.idDataComemorativa
    WHERE m.idMissa = ?";

$stmt = $conn->prepare($sqlMissa);
if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    include_once('../../includes/footer.php');
    exit;
}

$stmt->bind_param("i", $idMissa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="alert alert-danger">Missa não encontrada.</div>';
    $stmt->close();
    include_once('../../includes/footer.php');
    exit;
}

$missa = $result->fetch_assoc();
$stmt->close();

// Buscar músicas agrupadas por momento - versão corrigida
$sqlMusicas = "
    SELECT mm.idMusicaMomentoMissa, mo.idMomento, mu.idMusica, mu.NomeMusica, 
           mo.DescMomento, mo.OrdemDeExecucao
    FROM tbmissamusicas mm
    JOIN tbmusica mu ON mu.idMusica = mm.idMusica
    JOIN tbmomentosmissa mo ON mo.idMomento = mm.idMusicaMomentoMissa
    WHERE mm.idMissa = ?
    ORDER BY mo.OrdemDeExecucao, mu.NomeMusica";

$stmt = $conn->prepare($sqlMusicas);
if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta de músicas: ' . $conn->error . '</div>';
    include_once('../../includes/footer.php');
    exit;
}

$stmt->bind_param("i", $idMissa);
$stmt->execute();
$result = $stmt->get_result();
$musicas = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Agrupar por momento
$agrupadas = [];
foreach ($musicas as $m) {
    if (isset($m['DescMomento']) && isset($m['NomeMusica'])) {
        $momento = $m['DescMomento'];
        $agrupadas[$momento][] = $m['NomeMusica'];
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📖 Detalhes da Missa</h2>
        <a href="list.php" class="btn btn-secondary">← Voltar à lista</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informações Gerais</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($missa['DataMissa'])) ?></p>
                    <p><strong>Ano:</strong> <?= htmlspecialchars($missa['AnoMissa']) ?></p>
                    <p><strong>Título:</strong> <?= htmlspecialchars($missa['TituloMissa']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> 
                        <?= $missa['Status'] ? 
                            '<span class="badge bg-success">Ativa</span>' : 
                            '<span class="badge bg-secondary">Inativa</span>' ?>
                    </p>
                    <p><strong>Igreja:</strong> <?= htmlspecialchars($missa['NomeIgreja'] ?? '-') ?></p>
                    <p><strong>Comemoração:</strong> <?= htmlspecialchars($missa['DescComemoracao'] ?? '-') ?></p>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="btn-group">
                <a href="editar.php?id=<?= $idMissa ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="excluir.php?id=<?= $idMissa ?>" class="btn btn-danger btn-sm" 
                   onclick="return confirm('Tem certeza que deseja excluir esta missa?')">
                    <i class="bi bi-trash"></i> Excluir
                </a>
                <div class="dropdown d-inline">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-file-earmark"></i> Exportar
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="export_excel.php?id=<?= $idMissa ?>" class="dropdown-item">
                                <i class="bi bi-file-excel"></i> Excel
                            </a>
                        </li>
                        <li>
                            <a href="export_pdf.php?id=<?= $idMissa ?>" class="dropdown-item">
                                <i class="bi bi-file-pdf"></i> PDF
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Músicas da Missa</h5>
        </div>
        <div class="card-body">
            <?php if (count($agrupadas) > 0): ?>
                <div class="accordion" id="momentosAccordion">
                    <?php 
                    $contador = 1;
                    foreach ($agrupadas as $momento => $lista): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= $contador ?>">
                                <button class="accordion-button" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse<?= $contador ?>" 
                                        aria-expanded="true" 
                                        aria-controls="collapse<?= $contador ?>">
                                    <?= htmlspecialchars($momento) ?>
                                </button>
                            </h2>
                            <div id="collapse<?= $contador ?>" 
                                 class="accordion-collapse collapse show" 
                                 aria-labelledby="heading<?= $contador ?>" 
                                 data-bs-parent="#momentosAccordion">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <?php if(!empty($lista)): ?>
                                            <?php foreach ($lista as $musica): ?>
                                                <li class="list-group-item">
                                                    <?= htmlspecialchars($musica) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="list-group-item text-muted">Nenhuma música encontrada para este momento</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php $contador++; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    Nenhuma música vinculada a esta missa.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
