<?php
include '../conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Nova Música</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding: 20px;
    }
    .btn-topo {
      position: fixed;
      bottom: 20px;
      right: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="d-flex justify-content-between mb-3">
      <h2>Cadastrar Nova Música</h2>
      <div>
        <a href="musicas.php" class="btn btn-secondary">Voltar à Listagem</a>
        <a href="index.php" class="btn btn-outline-primary">Voltar à Página Anterior</a>
      </div>
    </div>
    <form action="musicas_salvar.php" method="POST">
      <div class="mb-3">
        <label for="nome" class="form-label">Nome da Música</label>
        <input type="text" class="form-control" name="NomeMusica" required>
      </div>
      <div class="mb-3">
        <label for="musica" class="form-label">Texto da Música</label>
        <textarea class="form-control" name="Musica" rows="5" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Momentos da Missa</label><br>
        <?php
        $res = $conn->query("SELECT * FROM tbmomentosmissa ORDER BY OrdemDeExecucao");
        while ($row = $res->fetch_assoc()) {
          echo '<div class="form-check form-check-inline">';
          echo '<input class="form-check-input" type="checkbox" name="momentos[]" value="' . $row['idMomento'] . '">';
          echo '<label class="form-check-label">' . $row['DescMomento'] . '</label>';
          echo '</div>';
        }
        ?>
      </div>
      <button type="submit" class="btn btn-success">Salvar Música</button>
    </form>
  </div>

  <a href="#" class="btn btn-primary btn-topo">Voltar ao Topo</a>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>