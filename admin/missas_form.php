<?php
include_once("../conexao.php");

// Inserção da Missa
if (isset($_POST['salvar'])) {
    $data = $_POST['DataMissa'];
    $ano = $_POST['AnoMissa'];
    $titulo = $_POST['TituloMissa'];
    $status = $_POST['Status'];
    $idIgreja = $_POST['idIgreja'];
    $idDataComemorativa = $_POST['idDataComemorativa'];
    $musicasSelecionadas = $_POST['musicas'] ?? [];

    $conn->query("INSERT INTO tbmissa (DataMissa, AnoMissa, TituloMissa, Status, idIgreja, idDataComemorativa)
                  VALUES ('$data', '$ano', '$titulo', '$status', '$idIgreja', '$idDataComemorativa')");
    $idMissa = $conn->insert_id;

    foreach ($musicasSelecionadas as $mm) {
        [$idMusica, $idMomento] = explode("_", $mm);
        $conn->query("INSERT INTO tbmissamusicas (idMusica, idMissa, idMusicaMomentoMissa)
                      VALUES ($idMusica, $idMissa, $idMomento)");
    }

    echo "<div class='alert alert-success'>Missa cadastrada com sucesso!</div>";
    echo "<script>setTimeout(() => window.location.href='missas.php', 1500);</script>";
    exit;
}

// Buscar dados para selects
$igrejas = $conn->query("SELECT idIgreja, NomeIgreja FROM tbIgreja ORDER BY NomeIgreja")->fetch_all(MYSQLI_ASSOC);
$datasComemorativas = $conn->query("SELECT idDataComemorativa, DescComemoracao FROM tbDataComemorativa ORDER BY DescComemoracao")->fetch_all(MYSQLI_ASSOC);
$momentos = $conn->query("SELECT idMomento, DescMomento FROM tbMomentosMissa ORDER BY OrdemDeExecucao")->fetch_all(MYSQLI_ASSOC);

// Buscar músicas por momento
$musicasPorMomento = [];
foreach ($momentos as $momento) {
    $id = $momento['idMomento'];
    $sql = "
    SELECT m.idMusica, m.NomeMusica
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
  <title>Cadastrar Missa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2 class="mb-3">➕ Cadastrar Nova Missa</h2>

  <form method="post">
    <div class="row mb-3">
      <div class="col-md-3">
        <label class="form-label">Data da Missa</label>
        <input type="date" name="DataMissa" class="form-control" required>
      </div>
      <div class="col-md-2">
        <label class="form-label">Ano</label>
        <input type="text" name="AnoMissa" class="form-control" required>
      </div>
      <div class="col-md-5">
        <label class="form-label">Título da Missa</label>
        <input type="text" name="TituloMissa" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="Status" class="form-select">
          <option value="1">Ativa</option>
          <option value="0">Inativa</option>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Igreja</label>
        <select name="idIgreja" class="form-select" required>
          <option value="">Selecione</option>
          <?php foreach ($igrejas as $i): ?>
            <option value="<?= $i['idIgreja'] ?>"><?= $i['NomeIgreja'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Data Comemorativa</label>
        <select name="idDataComemorativa" class="form-select">
          <option value="">Nenhuma</option>
          <?php foreach ($datasComemorativas as $d): ?>
            <option value="<?= $d['idDataComemorativa'] ?>"><?= $d['DescComemoracao'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <h5 class="mt-4 mb-2">🎵 Selecionar Músicas da Missa</h5>

    <?php foreach ($momentos as $mom): ?>
      <?php if (!empty($musicasPorMomento[$mom['idMomento']])): ?>
        <div class="mb-3">
          <strong><?= $mom['DescMomento'] ?></strong>
          <div class="row">
            <?php foreach ($musicasPorMomento[$mom['idMomento']] as $mus): ?>
              <div class="col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox"
                         name="musicas[]"
                         value="<?= $mus['idMusica'] . '_' . $mom['idMomento'] ?>"
                         id="check<?= $mus['idMusica'] ?>_<?= $mom['idMomento'] ?>">
                  <label class="form-check-label" for="check<?= $mus['idMusica'] ?>_<?= $mom['idMomento'] ?>">
                    <?= htmlspecialchars($mus['NomeMusica']) ?>
                  </label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>

    <div class="mt-4">
      <button type="submit" name="salvar" class="btn btn-success">💾 Salvar Missa</button>
      <a href="missas.php" class="btn btn-secondary">← Cancelar</a>
    </div>
  </form>
</div>
</body>
</html>
