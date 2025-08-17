<?php include_once("../conexao.php"); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Lista de Igrejas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-4">

  <h2>Igrejas Cadastradas</h2>

  <div class="mb-3">
    <a href="igreja_form.php" class="btn btn-success me-2">+ Nova Igreja</a>
    <a href="index.php" class="btn btn-secondary">⬅️ Voltar à Página Anterior</a>
  </div>

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
                  <a href='igreja_form.php?id={$igreja['idIgreja']}' class='btn btn-sm btn-primary me-1'>✏️ Editar</a>
                  <a href='igreja_excluir.php?id={$igreja['idIgreja']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Deseja realmente excluir esta igreja?')\">🗑️ Excluir</a>
                  
                </td>
              </tr>";
        }
      }
      ?>
    </tbody>
  </table>

</body>
</html>
