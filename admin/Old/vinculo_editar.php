<?php
include_once("../conexao.php");

$idIgreja = $_GET['idIgreja'] ?? null;
$idSacerdote = $_GET['idSacerdote'] ?? null;

if (!$idIgreja || !$idSacerdote) {
  echo "Erro: dados insuficientes.";
  exit;
}

// Busca dados do vínculo
$stmt = $conn->prepare("SELECT v.*, i.NomeIgreja, s.NomeSacerdote
                        FROM tbIgrejaSacerdote v
                        JOIN tbIgreja i ON v.idIgreja = i.idIgreja
                        JOIN tbSacerdotes s ON v.idSacerdote = s.idSacerdote
                        WHERE v.idIgreja = ? AND v.idSacerdote = ?");
$stmt->bind_param("ii", $idIgreja, $idSacerdote);
$stmt->execute();
$result = $stmt->get_result();
$vinculo = $result->fetch_assoc();

if (!$vinculo) {
  echo "Vínculo não encontrado.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Vínculo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h3>✏️ Editar Vínculo Igreja - Sacerdote</h3>

  <form action="vinculo_salvar.php" method="post">
    <input type="hidden" name="idIgreja" value="<?= htmlspecialchars($idIgreja) ?>">
    <input type="hidden" name="idSacerdote" value="<?= htmlspecialchars($idSacerdote) ?>">

    <div class="mb-3">
      <label class="form-label">Igreja:</label>
      <p class="form-control-plaintext"><?= htmlspecialchars($vinculo['NomeIgreja']) ?></p>
    </div>

    <div class="mb-3">
      <label class="form-label">Sacerdote:</label>
      <p class="form-control-plaintext"><?= htmlspecialchars($vinculo['NomeSacerdote']) ?></p>
    </div>

    <div class="mb-3">
      <label for="DataInicio" class="form-label">Data de Início:</label>
      <input type="date" id="DataInicio" name="DataInicio" class="form-control" value="<?= htmlspecialchars($vinculo['DataInicio']) ?>">
    </div>

    <div class="mb-3">
      <label for="DataFim" class="form-label">Data de Fim:</label>
      <input type="date" id="DataFim" name="DataFim" class="form-control" value="<?= htmlspecialchars($vinculo['DataFim']) ?>">
    </div>

    <div class="mb-3">
      <label for="Status" class="form-label">Status:</label>
      <select id="Status" name="Status" class="form-select" required>
        <option value="1" <?= $vinculo['Status'] == 1 ? 'selected' : '' ?>>Ativo</option>
        <option value="0" <?= $vinculo['Status'] == 0 ? 'selected' : '' ?>>Inativo</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="vinculo_list.php" class="btn btn-secondary">Cancelar</a>
  </form>

</body>
</html>
