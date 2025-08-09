<?php include_once("../conexao.php"); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Lista de Sacerdotes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h2>Sacerdotes Cadastrados</h2>

  <div class="mb-3">
    <a href="sacerdote_form.php" class="btn btn-success me-2">+ Novo Sacerdote</a>
    <a href="index.php" class="btn btn-secondary">⬅️ Voltar ao menu principal</a>
  </div>

  <?php if (isset($_GET['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
  <?php endif; ?>
  <?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success">Operação realizada com sucesso.</div>
  <?php endif; ?>

  <table class="table table-bordered table-striped">
    <thead class="table-light">
      <tr>
        <th>Nome</th>
        <th>Função</th>
        <th>Telefone</th>
        <th>Igrejas Vinculadas (Ativas)</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM tbSacerdotes ORDER BY NomeSacerdote";
      $res = $conn->query($sql);

      while ($sac = $res->fetch_assoc()) {
        $idSac = $sac['idSacerdote'];

        // Buscar igrejas vinculadas ativas
        $sqlIgrejas = "SELECT i.NomeIgreja 
                       FROM tbIgrejaSacerdote v
                       JOIN tbIgreja i ON v.idIgreja = i.idIgreja
                       WHERE v.idSacerdote = $idSac AND v.Status = 1
                       ORDER BY i.NomeIgreja";
        $resIgrejas = $conn->query($sqlIgrejas);

        $igrejasAtivas = [];
        while ($ig = $resIgrejas->fetch_assoc()) {
          $igrejasAtivas[] = $ig['NomeIgreja'];
        }
        $listaIgrejas = $igrejasAtivas ? implode(", ", $igrejasAtivas) : "<i>Sem vínculos ativos</i>";

        echo "<tr>
                <td>{$sac['NomeSacerdote']}</td>
                <td>{$sac['Funcao']}</td>
                <td>{$sac['Telefone']}</td>
                <td>$listaIgrejas</td>
                <td>
                  <a href='sacerdote_form.php?id={$sac['idSacerdote']}' class='btn btn-sm btn-primary me-1'>✏️ Editar</a>
                  <a href='sacerdote_excluir.php?id={$sac['idSacerdote']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Deseja realmente excluir este sacerdote?')\">🗑️ Excluir</a>
                </td>
              </tr>";
      }
      ?>
    </tbody>
  </table>

</body>
</html>
