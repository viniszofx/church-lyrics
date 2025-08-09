<?php include_once("../conexao.php"); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Vínculos Igreja x Sacerdote</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h2>Vínculos entre Igrejas e Sacerdotes</h2>

  <div class="mb-3">
    <a href="vinculaigrejasacerdote_form.php" class="btn btn-success me-2">+ Novo Vínculo</a>
    <a href="index.php" class="btn btn-secondary">⬅️ Voltar ao Menu</a>
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
        <th>Igreja</th>
        <th>Sacerdote</th>
        <th>Data Início</th>
        <th>Data Fim</th>
        <th>Status</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT v.*, i.NomeIgreja, s.NomeSacerdote
              FROM tbIgrejaSacerdote v
              JOIN tbIgreja i ON v.idIgreja = i.idIgreja
              JOIN tbSacerdotes s ON v.idSacerdote = s.idSacerdote
              ORDER BY i.NomeIgreja, s.NomeSacerdote";
      $res = $conn->query($sql);

      while ($row = $res->fetch_assoc()) {
        $status = $row['Status'] == 1 ? 'Ativo' : 'Inativo';
        $dataInicio = $row['DataInicio'] ? date('d/m/Y', strtotime($row['DataInicio'])) : '-';
        $dataFim = $row['DataFim'] ? date('d/m/Y', strtotime($row['DataFim'])) : '-';

        echo "<tr>
                <td>{$row['NomeIgreja']}</td>
                <td>{$row['NomeSacerdote']}</td>
                <td>$dataInicio</td>
                <td>$dataFim</td>
                <td>$status</td>
                <td>
                  <a href='vinculaigrejasacerdote_editar.php?idIgreja={$row['idIgreja']}&idSacerdote={$row['idSacerdote']}' class='btn btn-sm btn-primary me-1'>✏️ Editar</a>
                  <a href='vinculo_excluir.php?idIgreja={$row['idIgreja']}&idSacerdote={$row['idSacerdote']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Deseja realmente excluir este vínculo?')\">🗑️ Excluir</a>
                </td>
              </tr>";
      }
      ?>
    </tbody>
  </table>

</body>
</html>
