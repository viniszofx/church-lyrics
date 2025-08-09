<?php
include_once("../conexao.php");

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID da missa não informado.</div>";
    exit;
}

$idMissa = (int) $_GET['id'];

// Atualizar missa
if (isset($_POST['salvar'])) {
    $data = $_POST['DataMissa'];
    $ano = $_POST['AnoMissa'];
    $titulo = $_POST['TituloMissa'];
    $status = $_POST['Status'];
    $idIgreja = $_POST['idIgreja'];
    $idDataComemorativa = $_POST['idDataComemorativa'];
    $musicasSelecionadas = $_POST['musicas'] ?? [];

    $conn->query("UPDATE tbmissa SET
        DataMissa = '$data',
        AnoMissa = '$ano',
        TituloMissa = '$titulo',
        Status = '$status',
        idIgreja = '$idIgreja',
        idDataComemorativa = '$idDataComemorativa'
        WHERE idMissa = $idMissa");

    // Limpar vínculos antigos
    $conn->query("DELETE FROM tbmissamusicas WHERE idMissa = $idMissa");

    // Inserir novos vínculos
    foreach ($musicasSelecionadas as $mm) {
        [$idMusica, $idMomento] = explode("_", $mm);
        $conn->query("INSERT INTO tbmissamusicas (idMusica, idMissa, idMusicaMomentoMissa)
                      VALUES ($idMusica, $idMissa, $idMomento)");
    }

    echo "<div class='alert alert-success'>Missa atualizada com sucesso!</div>";
    echo "<script>setTimeout(() => window.location.href='missas.php', 1500);</script>";
    exit;
}

// Dados da missa
$missa = $conn->query("SELECT * FROM tbmissa WHERE idMissa = $idMissa")->fetch_assoc();

// Listas auxiliares
$igrejas = $conn->query("SELECT idIgreja, NomeIgreja FROM tbIgreja ORDER BY NomeIgreja")->fetch_all(MYSQLI_ASSOC);
$datasComemorativas = $conn->query("SELECT idDataComemorativa, DescComemoracao FROM tbDataComemorativa ORDER BY DescComemoracao")->fetch_all(MYSQLI_ASSOC);
$momentos = $conn->query("SELECT idMomento, DescMomento FROM tbMomentosMissa ORDER BY OrdemDeExecucao")->fetch_all(MYSQLI_ASSOC);

// Músicas por momento
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

// Músicas já associadas
$musicasMarcadas = [];
$consulta = $conn->query("SELECT idMusica, idMusicaMomentoMissa FROM tbmissamusicas WHERE idMissa = $idMissa");
while ($r = $consulta->fetch_assoc()) {
    $musicasMarcadas[] = $r['idMusica'] . '_' . $r['idMusicaMomentoMissa'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Missa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2>✏️ Editar Missa</h2>

  <form method="post">
    <div class="row mb-3">
      <div class="col-md-3">
        <label class="form-label">Data da Missa</label>
        <input type="date" name="DataMissa" class="form-control" value="<?= $missa['DataMissa'] ?>" required>
      </div>
      <div class="col-md-2">
        <label class="form-label">Ano</label>
        <input type="text" name="AnoMissa" class="form-control" value="<?= $missa['AnoMissa'] ?>" required>
      </div>
      <div class="col-md-5">
        <label class="form-label">Título da Missa</label>
        <input type="text" name="TituloMissa" class="form-control" value="<?= htmlspecialchars($missa['TituloMissa']) ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="Status" class="form-select">
          <option value="1" <?= $missa['Status'] == 1 ? 'selected' : '' ?>>Ativa</option>
          <option value="0" <?= $missa['Status'] == 0 ? 'selected' : '' ?>>Inativa</option>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Igreja</label>
        <select name="idIgreja" class="form-select" required>
          <option value="">Selecione</option>
          <?php foreach ($igrejas as $i): ?>
            <option value="<?= $i['idIgreja'] ?>" <?= $missa['idIgreja'] == $i['idIgreja'] ? 'selected' : '' ?>>
              <?= $i['NomeIgreja'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Data Comemorativa</label>
        <select name="idDataComemorativa" class="form-select">
          <option value="">Nenhuma</option>
          <?php foreach ($datasComemorativas as $d): ?>
            <option value="<?= $d['idDataComemorativa'] ?>" <?= $missa['idDataComemorativa'] == $d['idDataComemorativa'] ? 'selected' : '' ?>>
              <?= $d['DescComemoracao'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <h5 class="mt-4 mb-2">🎵 Músicas da Missa</h5>
    <div class="mt-4">
      <button type="submit" name="salvar" class="btn btn-success">💾 Salvar Alterações</button>
      <a href="missas.php" class="btn btn-secondary">← Cancelar</a>
    </div>


    <?php foreach ($momentos as $mom): ?>
      <?php if (!empty($musicasPorMomento[$mom['idMomento']])): ?>
        <div class="mb-3">
          <strong><?= $mom['DescMomento'] ?></strong>
          <div class="row">
            <?php foreach ($musicasPorMomento[$mom['idMomento']] as $mus): 
              $value = $mus['idMusica'] . '_' . $mom['idMomento'];
              ?>
              <div class="col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox"
                         name="musicas[]"
                         value="<?= $value ?>"
                         id="check<?= $value ?>"
                         <?= in_array($value, $musicasMarcadas) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="check<?= $value ?>">
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
      <button type="submit" name="salvar" class="btn btn-success">💾 Salvar Alterações</button>
      <a href="missas.php" class="btn btn-secondary">← Cancelar</a>
    </div>
  </form>
</div>
</body>
</html>
