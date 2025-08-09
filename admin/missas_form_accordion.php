<?php
include_once("../conexao.php");

// Inserção
if (isset($_POST['salvar'])) {
    $data = $_POST['DataMissa'];
    $ano = $_POST['AnoMissa'];
    $titulo = $_POST['TituloMissa'];
    $status = $_POST['Status'];
    $idIgreja = $_POST['idIgreja'];
    $idDataComemorativa = $_POST['idDataComemorativa'];
    $selecionadas = $_POST['musicas'] ?? [];

    $conn->query("INSERT INTO tbmissa (DataMissa, AnoMissa, TituloMissa, Status, idIgreja, idDataComemorativa)
                  VALUES ('$data', '$ano', '$titulo', '$status', '$idIgreja', '$idDataComemorativa')");
    $idMissa = $conn->insert_id;

    foreach ($selecionadas as $item) {
        [$idMusica, $idMomento] = explode("_", $item);
        $conn->query("INSERT INTO tbmissamusicas (idMusica, idMissa, idMusicaMomentoMissa)
                      VALUES ($idMusica, $idMissa, $idMomento)");
    }

    echo "<script>alert('Missa cadastrada com sucesso!'); window.location='missas.php';</script>";
    exit;
}

// Dados auxiliares
$igrejas = $conn->query("SELECT idIgreja, NomeIgreja FROM tbIgreja")->fetch_all(MYSQLI_ASSOC);
$datas = $conn->query("SELECT idDataComemorativa, DescComemoracao FROM tbDataComemorativa")->fetch_all(MYSQLI_ASSOC);
$momentos = $conn->query("SELECT idMomento, DescMomento FROM tbMomentosMissa ORDER BY OrdemDeExecucao")->fetch_all(MYSQLI_ASSOC);

// Músicas agrupadas por momento
$musicasPorMomento = [];
foreach ($momentos as $momento) {
    $id = $momento['idMomento'];
    $sql = "
    SELECT m.idMusica, m.NomeMusica, m.Musica
    FROM tbMusicaMomentoMissa mm
    JOIN tbMusica m ON m.idMusica = mm.idMusica
    WHERE mm.idMomento = $id
    ORDER BY m.NomeMusica";
    $musicasPorMomento[$id] = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Missa (Accordion)</title>
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
  <h2>🎵 Cadastrar Nova Missa (Accordion)</h2>
    <div class="mt-4">
      <button type="submit" name="salvar" class="btn btn-success">💾 Salvar Alterações</button>
      <a href="missas.php" class="btn btn-secondary">← Cancelar e Sair</a>
    </div>

  <form method="post">
    <div class="row mb-3">
      <div class="col-md-3">
        <label>Data da Missa</label>
        <input type="date" name="DataMissa" class="form-control" required>
      </div>
      <div class="col-md-2">
        <label>Ano</label>
        <input type="text" name="AnoMissa" class="form-control" required>
      </div>
      <div class="col-md-5">
        <label>Título</label>
        <input type="text" name="TituloMissa" class="form-control">
      </div>
      <div class="col-md-2">
        <label>Status</label>
        <select name="Status" class="form-select">
          <option value="1">Ativa</option>
          <option value="0">Inativa</option>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label>Igreja</label>
        <select name="idIgreja" class="form-select" required>
          <option value="">Selecione</option>
          <?php foreach ($igrejas as $i): ?>
            <option value="<?= $i['idIgreja'] ?>"><?= $i['NomeIgreja'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label>Data Comemorativa</label>
        <select name="idDataComemorativa" class="form-select">
          <option value="">Nenhuma</option>
          <?php foreach ($datas as $d): ?>
            <option value="<?= $d['idDataComemorativa'] ?>"><?= $d['DescComemoracao'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <h5 class="mb-3">🎶 Músicas por Momento</h5>

    <div class="accordion" id="accordionMusicas">
      <?php foreach ($momentos as $index => $mom): ?>
        <?php $musicas = $musicasPorMomento[$mom['idMomento']] ?? []; ?>
        <?php if (count($musicas) > 0): ?>
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading<?= $index ?>">
            <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
              <?= $mom['DescMomento'] ?>
            </button>
          </h2>
          <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#accordionMusicas">
            <div class="accordion-body">
              <?php foreach ($musicas as $mus): 
                $value = $mus['idMusica'] . '_' . $mom['idMomento']; ?>
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" name="musicas[]" value="<?= $value ?>" id="mus<?= $value ?>">
                  <label class="form-check-label fw-bold" for="mus<?= $value ?>"><?= htmlspecialchars($mus['NomeMusica']) ?></label>
                  <div class="preview-musica text-muted small"><?= nl2br(htmlspecialchars(substr($mus['Musica'], 0, 500))) ?></div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="mt-4">
      <button type="submit" name="salvar" class="btn btn-success">💾 Salvar Missa</button>
      <a href="missas.php" class="btn btn-secondary">← Cancelar e Sair</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
