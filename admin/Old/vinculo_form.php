<?php
include_once("../conexao.php");

$idIgreja = isset($_GET['idIgreja']) ? $_GET['idIgreja'] : null;
$idSacerdote = isset($_GET['idSacerdote']) ? $_GET['idSacerdote'] : null;


$dataInicio = $dataFim = $status = "";

if ($idIgreja && $idSacerdote) {
  $stmt = $conn->prepare("SELECT * FROM tbIgrejaSacerdote WHERE idIgreja = ? AND idSacerdote = ?");
  $stmt->bind_param("ii", $idIgreja, $idSacerdote);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $dataInicio = $row['DataInicio'];
    $dataFim = $row['DataFim'];
    $status = $row['Status'];
  }
}

// Buscar listas
$igrejas = $conn->query("SELECT idIgreja, NomeIgreja FROM tbIgreja ORDER BY NomeIgreja");
$sacerdotes = $conn->query("SELECT idSacerdote, NomeSacerdote FROM tbSacerdotes ORDER BY NomeSacerdote");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= ($idIgreja && $idSacerdote) ? 'Editar Vínculo' : 'Novo Vínculo' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h2><?= ($idIgreja && $idSacerdote) ? 'Editar Vínculo' : 'Novo Vínculo' ?></h2>

  <form action="vinculo_salvar.php" method="post">
    <div class="mb-3">
      <label for="idIgreja" class="form-label">Igreja</label>
      <select name="idIgreja" id="idIgreja" class="form-select" required <?= $idIgreja ? 'disabled' : '' ?> >
        <option value="">Selecione</option>
        <?php while ($ig = $igrejas->fetch_assoc()): ?>
          <option value="<?= $ig['idIgreja'] ?>" <?= ($ig['idIgreja'] == $idIgreja) ? 'selected' : '' ?>><?= $ig['NomeIgreja'] ?></option>
        <?php endwhile; ?>
      </select>
      <?php if ($idIgreja): ?><input type="hidden" name="idIgreja" value="<?= $idIgreja ?>"><?php endif; ?>
    </div>

    <div class="mb-3">
      <label for="idSacerdote" class="form-label">Sacerdote</label>
      <select name="idSacerdote" id="idSacerdote" class="form-select" required <?= $idSacerdote ? 'disabled' : '' ?> >
        <option value="">Selecione</option>
        <?php while ($sac = $sacerdotes->fetch_assoc()): ?>
          <option value="<?= $sac['idSacerdote'] ?>" <?= ($sac['idSacerdote'] == $idSacerdote) ? 'selected' : '' ?>><?= $sac['NomeSacerdote'] ?></option>
        <?php endwhile; ?>
      </select>
      <?php if ($idSacerdote): ?><input type="hidden" name="idSacerdote" value="<?= $idSacerdote ?>"><?php endif; ?>
    </div>

    <div class="mb-3">
      <label for="dataInicio" class="form-label">Data de Início</label>
      <input type="date" id="dataInicio" name="dataInicio" class="form-control" value="<?= htmlspecialchars($dataInicio) ?>">
    </div>

    <div class="mb-3">
      <label for="dataFim" class="form-label">Data de Fim</label>
      <input type="date" id="DataFim" name="DataFim" class="form-control" value="<?= htmlspecialchars($DataFim) ?>">
    </div>

    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select id="status" name="status" class="form-select" required>
        <option value="1" <?= $status === "1" ? 'selected' : '' ?>>Ativo</option>
        <option value="0" <?= $status === "0" ? 'selected' : '' ?>>Inativo</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="vinculo_list.php" class="btn btn-secondary">Cancelar</a>
  </form>

</body>
</html>
