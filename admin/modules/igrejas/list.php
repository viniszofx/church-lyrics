<?php include_once("../../includes/header.php"); ?>

<h2>Igrejas Cadastradas</h2>

<div class="mb-3">
  <a href="form.php" class="btn btn-success me-2">+ Nova Igreja</a>
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
      <th>Endereço</th>
      <th>Telefone</th>
      <th>Tipo</th>
      <th>Sacerdotes Vinculados</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "SELECT * FROM tbigreja ORDER BY NomeIgreja";
    $res = $conn->query($sql);
    
    // Check if query was successful
    if (!$res) {
      echo '<div class="alert alert-danger">Erro na consulta SQL: ' . $conn->error . '<br>Query: ' . $sql . '</div>';
    } elseif ($res->num_rows == 0) {
      echo '<tr><td colspan="6" class="text-center">Nenhuma igreja encontrada</td></tr>';
    } else {
      while ($igreja = $res->fetch_assoc()) {
        // Buscar sacerdotes vinculados ativos a essa igreja
        $idIgreja = $igreja['idIgreja'];
        $sqlSacerdotes = "SELECT s.NomeSacerdote 
                          FROM tbigrejasacerdote v
                          JOIN tbsacerdotes s ON v.idSacerdote = s.idSacerdote
                          WHERE v.idIgreja = $idIgreja AND v.Status = 1
                          ORDER BY s.NomeSacerdote";
        $resSacerdotes = $conn->query($sqlSacerdotes);
        
        $sacerdotesAtivos = [];
        if ($resSacerdotes) {
          while ($sac = $resSacerdotes->fetch_assoc()) {
            $sacerdotesAtivos[] = $sac['NomeSacerdote'];
          }
        } else {
          echo '<div class="alert alert-danger">Erro ao buscar sacerdotes: ' . $conn->error . '</div>';
        }
        $listaSacerdotes = $sacerdotesAtivos ? implode(", ", $sacerdotesAtivos) : "<i>Sem vínculos ativos</i>";

        echo "<tr>
                <td>{$igreja['NomeIgreja']}</td>
                <td>{$igreja['Endereco']}</td>
                <td>{$igreja['Telefone']}</td>
                <td>{$igreja['Tipo']}</td>
                <td>$listaSacerdotes</td>
                <td>
                  <a href='form.php?id={$igreja['idIgreja']}' class='btn btn-sm btn-primary me-1'>✏️ Editar</a>
                  <a href='excluir.php?id={$igreja['idIgreja']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Deseja realmente excluir esta igreja?')\">🗑️ Excluir</a>
                </td>
              </tr>";
      }
    }
    ?>
  </tbody>
</table>

<?php include_once("../../includes/footer.php"); ?>
