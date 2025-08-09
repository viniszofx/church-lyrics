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

// Listas para selects
$igrejas = $conn->query("SELECT idIgreja, NomeIgreja FROM tbIgreja")->fetch_all(MYSQLI_ASSOC);
$datas = $conn->query("SELECT idDataComemorativa, DescComemoracao FROM tbDataComemorativa")->fetch_all(MYSQLI_ASSOC);
$momentos = $conn->query("SELECT idMomento, DescMomento FROM tbMomentosMissa ORDER BY OrdemDeExecucao")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Missa (Lista AJAX)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    .preview-musica {
      max-height: 6.5em; overflow: hidden;
      white-space: pre-wrap; font-family: inherit;
    }
  </style>
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2 class="mb-3">🎵 Cadastrar Nova Missa (Lista por Momento)</h2>

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
      <div class="col-md-4">
        <label>Título</label>
        <input type="text" name="TituloMissa" class="form-control">
      </div>
      <div class="col-md-3">
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

    <div class="mb-4">
      <label><strong>Filtrar Músicas por Momento da Missa</strong></label>
      <select id="filtroMomento" class="form-select">
        <option value="">-- Selecione o Momento --</option>
        <?php foreach ($momentos as $m): ?>
          <option value="<?= $m['idMomento'] ?>"><?= $m['DescMomento'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div id="musicasLista" class="mb-4">
      <!-- Músicas carregadas por AJAX aparecem aqui -->
    </div>

    <div class="mt-4">
      <button type="submit" name="salvar" class="btn btn-success">💾 Salvar Missa</button>
      <a href="missas.php" class="btn btn-secondary">← Cancelar</a>
    </div>
  </form>
</div>

<script>
$('#filtroMomento').on('change', function() {
    var idMomento = $(this).val();
    if (!idMomento) {
        $('#musicasLista').html('');
        return;
    }

    $.get('musicas_ajax.php', { momento: idMomento }, function(data) {
        $('#musicasLista').html(data);
    });
});
</script>
</body>
</html>
