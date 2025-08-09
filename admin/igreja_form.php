<?php include_once("../conexao.php");

$id = $_GET['id'] ?? null;
$nome = $endereco = $telefone = $tipo = "";

if ($id) {
    $res = $conn->query("SELECT * FROM tbIgreja WHERE idIgreja = $id");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $nome = $row['NomeIgreja'];
        $endereco = $row['Endereco'];
        $telefone = $row['Telefone'];
        $tipo = $row['Tipo'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title><?= $id ? 'Editar Igreja' : 'Nova Igreja' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-4">
  <?php if (isset($_GET['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
  <?php endif; ?>

  <?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success">Operação realizada com sucesso.</div>
  <?php endif; ?>
  <h2><?= $id ? 'Editar Igreja' : 'Nova Igreja' ?></h2>

  <form action="igreja_salvar.php" method="post">
    <?php if ($id): ?>
      <input type="hidden" name="id" value="<?= $id ?>">
    <?php endif; ?>

    <div class="mb-3">
      <label for="nome" class="form-label">Nome da Igreja</label>
      <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
    </div>

    <div class="mb-3">
      <label for="endereco" class="form-label">Endereço</label>
      <input type="text" id="endereco" name="endereco" class="form-control" value="<?= htmlspecialchars($endereco) ?>" >
    </div>

    <div class="mb-3">
      <label for="telefone" class="form-label">Telefone</label>
      <input type="text" id="telefone" name="telefone" class="form-control" value="<?= htmlspecialchars($telefone) ?>" >
    </div>

    <div class="mb-3">
      <label for="tipo" class="form-label">Tipo</label>
      <select id="tipo" name="tipo" class="form-select" >
        <option value="">Selecione o tipo</option>
        <option value="Paróquia" <?= trim($tipo) == 'Paróquia' ? 'selected' : '' ?>>Paróquia</option>
        <option value="Santuário" <?= trim($tipo) == 'Santuário' ? 'selected' : '' ?>>Santuário</option>
        <option value="Diocese"  <?= trim($tipo) == 'Diocese'  ? 'selected' : '' ?>>Diocese</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="igreja_list.php" class="btn btn-secondary">Cancelar</a>
  </form>

</body>
</html>
