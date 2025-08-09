<?php
include_once("../conexao.php");

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID da missa não informado.</div>";
    exit;
}

$idMissa = (int) $_GET['id'];

// Atualizar
if (isset($_POST['salvar'])) {
    $data = $_POST['DataMissa'];
    $ano = $_POST['AnoMissa'];
    $titulo = $_POST['TituloMissa'];
    $status = $_POST['Status'];
    $idIgreja = $_POST['idIgreja'];
    $idDataComemorativa = $_POST['idDataComemorativa'];
    $musicasSelecionadas = $_POST['musicas'] ?? [];

    $conn->query("UPDATE tbmissa SET DataMissa='$data', AnoMissa='$ano', TituloMissa='$titulo', Status='$status',
        idIgreja='$idIgreja', idDataComemorativa='$idDataComemorativa' WHERE idMissa = $idMissa");

    $conn->query("DELETE FROM tbmissamusicas WHERE idMissa = $idMissa");

    foreach ($musicasSelecionadas as $idMomento => $idMusica) {
        $conn->query("INSERT INTO tbmissamusicas (idMusica, idMissa, idMusicaMomentoMissa)
                      VALUES ($idMusica, $idMissa, $idMomento)");
    }

    echo "<script>alert('Missa atualizada!'); window.location='missas.php';</script>";
    exit;
}

// Dados da missa
$missa = $conn->query("SELECT * FROM tbmissa WHERE idMissa = $idMissa")->fetch_assoc();
$igrejas = $conn->query("SELECT idIgreja, NomeIgreja FROM tbIgreja")->fetch_all(MYSQLI_ASSOC);
$datas = $conn->query("SELECT idDataComemorativa, DescComemoracao FROM tbDataComemorativa")->fetch_all(MYSQLI_ASSOC);
$momentos = $conn->query("SELECT idMomento, DescMomento FROM tbMomentosMissa ORDER BY OrdemDeExecucao")->fetch_all(MYSQLI_ASSOC);

// Músicas por momento
$musicasPorMomento = [];
foreach ($momentos as $m) {
    $id = $m['idMomento'];
    $sql = "SELECT m.idMusica, m.NomeMusica, m.Musica
            FROM tbMusicaMomentoMissa mm
            JOIN tbMusica m ON m.idMusica = mm.idMusica
            WHERE mm.idMomento = $id
            ORDER BY m.NomeMusica";
    $musicasPorMomento[$id] = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

// Selecionadas
$selecionadas = [];
$res = $conn->query("SELECT idMusica, idMusicaMomentoMissa FROM tbmissamusicas WHERE idMissa = $idMissa");
while ($r = $res->fetch_assoc()) {
    $selecionadas[$r['idMusicaMomentoMissa']] = $r['idMusica'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Missa (Accordion)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .preview-musica {
      max-height: 6.5em;
      overflow: hidden;
      white-space: pre-wrap;
      font-family: inherit;
    }
  </style>
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2>✏️ Editar Missa (Accordion)</h2>

  <form method="post">
    <div class="row mb-3">
      <div class="col-md-3">
        <label>Data</label>
        <input type="date" name="DataMissa" value="<?= $missa['DataMissa'] ?>" class="form-control" required>
      </div>
      <div class="col-md-2">
        <label>Ano</label>
        <input type="text" name="AnoMissa" value="<?= $missa['AnoMissa'] ?>" class="form-control" required>
      </div>
      <div class="col-md-5">
        <label>Título</label>
        <input type="text" name="TituloMissa" value="<?= htmlspecialchars($missa['TituloMissa']) ?>" class="form-control">
      </div>
      <div class="col-md-2">
        <label>Status</label>
        <select name="Status" class="form-select">
          <option value="1" <?= $missa['Status'] == 1 ? 'selected' : '' ?>>Ativa</option>
          <option value="0" <?= $missa['Status'] == 0 ? 'selected' : '' ?>>Inativa</option>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label>Igreja</label>
        <select name="idIgreja" class="form-select">
          <?php foreach ($igrejas as $i): ?>
            <option value="<?= $i['idIgreja'] ?>" <?= $missa['idIgreja'] == $i['idIgreja'] ? 'selected' : '' ?>>
              <?= $i['NomeIgreja'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label>Data Comemorativa</label>
        <select name="idDataComemorativa" class="form-select">
          <option value="">Nenhuma</option>
          <?php foreach ($datas as $d): ?>
            <option value="<?= $d['idDataComemorativa'] ?>" <?= $missa['idDataComemorativa'] == $d['idDataComemorativa'] ? 'selected' : '' ?>>
              <?= $d['DescComemoracao'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <h5>Músicas por Momento</h5>
    <div class="mt-4">
      <button type="submit" name="salvar" class="btn btn-success">💾 Salvar Alterações</button>
      <a href="missas.php" class="btn btn-secondary">← Cancelar e Sair</a>
    </div>

    <div class="accordion" id="accordionMusicas">
      <?php foreach ($momentos as $index => $mom): 
        $idMomento = $mom['idMomento'];
        $musicas = $musicasPorMomento[$idMomento] ?? []; ?>
        <?php if ($musicas): ?>
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading<?= $index ?>">
            <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
              <?= $mom['DescMomento'] ?>
            </button>
          </h2>
          <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index == 0 ? 'show' : '' ?>" data-bs-parent="#accordionMusicas">
            <div class="accordion-body">
              <?php foreach ($musicas as $mus): 
                $checked = (isset($selecionadas[$idMomento]) && $selecionadas[$idMomento] == $mus['idMusica']) ? 'checked' : '';
                ?>
                <div class="form-check mb-2">
                  <input type="radio" name="musicas[<?= $idMomento ?>]" value="<?= $mus['idMusica'] ?>" id="mus<?= $idMomento ?>_<?= $mus['idMusica'] ?>" class="form-check-input" <?= $checked ?>>
                  <label class="form-check-label fw-bold" for="mus<?= $idMomento ?>_<?= $mus['idMusica'] ?>">
                    <?= htmlspecialchars($mus['NomeMusica']) ?>
                  </label>
                  <div class="preview-musica text-muted small"><?= (htmlspecialchars(substr($mus['Musica'], 0, 500))) ?></div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="mt-4">
      <button type="submit" name="salvar" class="btn btn-success">💾 Salvar Alterações</button>
      <a href="missas.php" class="btn btn-secondary">← Cancelar e Sair</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
