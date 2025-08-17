<?php 
include_once("../../includes/header.php");

$id = $_GET['id'] ?? null;
$nome = $funcao = $telefone = "";

if ($id) {
    $res = $conn->query("SELECT * FROM tbsacerdotes WHERE idSacerdote = $id");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $nome = $row['NomeSacerdote'];
        $funcao = $row['Funcao'];
        $telefone = $row['Telefone'];
    }
}
?>

<h2><?= $id ? 'Editar Sacerdote' : 'Novo Sacerdote' ?></h2>

<form action="salvar.php" method="post">
  <?php if ($id): ?>
    <input type="hidden" name="id" value="<?= $id ?>">
  <?php endif; ?>

  <div class="mb-3">
    <label for="nome" class="form-label">Nome do Sacerdote</label>
    <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
  </div>

  <div class="mb-3">
    <label for="funcao" class="form-label">Função</label>
    <input type="text" id="funcao" name="funcao" class="form-control" value="<?= htmlspecialchars($funcao) ?>" required>
  </div>

  <div class="mb-3">
    <label for="telefone" class="form-label">Telefone</label>
    <input type="text" id="telefone" name="telefone" class="form-control" value="<?= htmlspecialchars($telefone) ?>" required>
  </div>

  <button type="submit" class="btn btn-primary">Salvar</button>
  <a href="list.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once("../../includes/footer.php"); ?>
