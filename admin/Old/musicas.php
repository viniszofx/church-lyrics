<?php
include '../conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Lista de Músicas</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .musica-preview {
      white-space: pre-line;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 5;
      -webkit-box-orient: vertical;
    }
  </style>
</head>
<body>
<div class="container mt-5" id="topo">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Listagem de Músicas</h2>
    <div>
      <a href="musicas_form.php" class="btn btn-success">Cadastrar Nova Música</a>
      <a href="index.php" class="btn btn-secondary ml-2">Voltar à Página Anterior</a>
    </div>
  </div>

  <!-- Filtros -->
  <form id="filtroForm" method="GET" class="form-inline mb-4">
    <input type="text" name="nome" class="form-control mr-2" placeholder="Buscar por nome..." value="<?= isset($_GET['nome']) ? htmlspecialchars($_GET['nome']) : '' ?>">
    <select name="momento" class="form-control mr-2">
      <option value="">Todos os Momentos</option>
      <?php
      $momentoQuery = $conn->query("SELECT idMomento, DescMomento FROM tbmomentosmissa ORDER BY OrdemDeExecucao");
      while ($row = $momentoQuery->fetch_assoc()) {
        $selected = (isset($_GET['momento']) && $_GET['momento'] == $row['idMomento']) ? 'selected' : '';
        echo "<option value='{$row['idMomento']}' $selected>{$row['DescMomento']}</option>";
      }
      ?>
    </select>
    <button type="submit" class="btn btn-primary">Filtrar</button>
  </form>

  <!-- Listagem -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th>Nome da Música</th>
          <th>Música (Resumo)</th>
          <th>Momento da Missa</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $where = "1=1";
        if (!empty($_GET['nome'])) {
          $nome = $conn->real_escape_string($_GET['nome']);
          $where .= " AND m.NomeMusica LIKE '%$nome%'";
        }
        if (!empty($_GET['momento'])) {
          $momento = intval($_GET['momento']);
          $where .= " AND mm.idMomento = $momento";
        }

        $sql = "
          SELECT m.idMusica, m.NomeMusica, m.Musica, mo.DescMomento
          FROM tbmusica m
          LEFT JOIN tbmusicamomentomissa mm ON m.idMusica = mm.idMusica
          LEFT JOIN tbmomentosmissa mo ON mo.idMomento = mm.idMomento
          WHERE $where
          ORDER BY mo.OrdemDeExecucao ASC
        ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
        ?>
          <tr>
            <td><?= htmlspecialchars($row['NomeMusica']) ?></td>
            <td><div class="musica-preview"><?= nl2br(htmlspecialchars($row['Musica'])) ?></div></td>
            <td><?= htmlspecialchars($row['DescMomento']) ?></td>
             
          <td class="text-nowrap">
              <!-- Botão Editar -->
              <a href="musicas_editar.php?id=<?= $row['idMusica'] ?>" 
                class="btn btn-sm btn-primary" 
                title="Editar Música">
                  Editar
              </a>

              <!-- Botão Visualizar -->
              <a href="musicas_visualizar.php?id=<?= $row['idMusica'] ?>" 
                class="btn btn-sm btn-info" 
                title="Visualizar Música">
                  Visualizar
              </a>

              <!-- Botão Excluir com confirmação usando nome da música -->
              <a href="musicas_excluir.php?id=<?= $row['idMusica'] ?>" 
                class="btn btn-sm btn-danger" 
                onclick="return confirm('Tem certeza que deseja excluir a música: <?= addslashes($row['NomeMusica']) ?>?');" 
                title="Excluir Música">
                  Excluir
              </a>
           </td>

          </tr>
        <?php
          endwhile;
        else:
        ?>
          <tr><td colspan="4" class="text-center">Nenhuma música encontrada.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Botão de voltar ao topo -->
  <div class="text-center mt-4">
    <a href="#topo" class="btn btn-outline-primary">Voltar ao Topo</a>
  </div>
</div>
</body>
</html>
