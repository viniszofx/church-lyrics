<?php
include_once("../conexao.php");

// Receber filtros via GET
$ano = $_GET['ano'] ?? '';
$data = $_GET['data'] ?? '';
$status = $_GET['status'] ?? '';
$titulo = $_GET['titulo'] ?? '';
$comemoracao = $_GET['comemoracao'] ?? '';

// Consulta com filtros
$sql = "
SELECT m.idMissa, m.DataMissa, m.AnoMissa, m.TituloMissa, m.Status,
       i.NomeIgreja, dc.DescComemoracao
FROM tbmissa m
LEFT JOIN tbigreja i ON m.idIgreja = i.idIgreja
LEFT JOIN tbdatacomemorativa dc ON m.idDataComemorativa = dc.idDataComemorativa
WHERE 1=1
";

if ($ano) $sql .= " AND m.AnoMissa = '$ano'";
if ($data) $sql .= " AND m.DataMissa = '$data'";
if ($titulo) $sql .= " AND m.TituloMissa LIKE '%$titulo%'";
if ($status !== '') $sql .= " AND m.Status = '$status'";
if ($comemoracao) $sql .= " AND dc.DescComemoracao LIKE '%$comemoracao%'";

$sql .= " ORDER BY m.DataMissa DESC";

$missas = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Missas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2 class="mb-3">📅 Missas Cadastradas</h2>

  <!-- Filtros -->
  <form method="get" class="row g-2 mb-4">
    <div class="col-md-2">
      <input type="text" name="ano" class="form-control" placeholder="Ano" value="<?= htmlspecialchars($ano) ?>">
    </div>
    <div class="col-md-2">
      <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($data) ?>">
    </div>
    <div class="col-md-2">
      <select name="status" class="form-select">
        <option value="">Status</option>
        <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Ativa</option>
        <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Inativa</option>
      </select>
    </div>
    <div class="col-md-3">
      <input type="text" name="comemoracao" class="form-control" placeholder="Comemoração" value="<?= htmlspecialchars($comemoracao) ?>">
    </div>
    <div class="col-md-3">
      <input type="text" name="titulo" class="form-control" placeholder="Título da Missa" value="<?= htmlspecialchars($titulo) ?>">
    </div>
    <div class="col-md-12 mt-2 text-end">
      <button type="submit" class="btn btn-primary">🔍 Filtrar</button>
    </div>
  </form>

  <!-- Botões principais -->
  <div class="mb-3 text-end">
<!--    <a href="missas_form.php" class="btn btn-success">➕ Cadastrar nova missa</a>    
        mudando o formulário em função das musicas que estava com checbox--> 
<!--    <a href="missas_form_lista.php" class="btn btn-success">➕ Cadastrar nova missa</a>   -->
    <a href="missas_form_accordion.php" class="btn btn-success">➕ Cadastrar nova missa</a>  
    <a href="index.php" class="btn btn-secondary">⬅️  Voltar ao menu principal</a>
  </div>

  <!-- Tabela de Missas -->
  <?php if (count($missas)): ?>
    <div class="table-responsive">
      <table class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th>Data</th>
                <th>Ano</th>
                <th>Status</th>
                <th>Igreja</th>
                <th>Comemoração</th>
                <th>Título</th>
                <th>Ações</th> <!-- ← nova coluna -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($missas as $m): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($m['DataMissa'])) ?></td>
                    <td><?= htmlspecialchars($m['AnoMissa']) ?></td>
                    <td><?= $m['Status'] ? 'Ativa' : 'Inativa' ?></td>
                    <td><?= htmlspecialchars($m['NomeIgreja'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($m['DescComemoracao'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($m['TituloMissa']) ?></td>
                    <td>
                        <a href="missas_editar_accordion.php?id=<?= $m['idMissa'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                        <a href="missas_excluir.php?id=<?= $m['idMissa'] ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Deseja realmente excluir esta missa?')">🗑️ Excluir</a>
                        <a href="missas_detalhes.php?id=<?= $m['idMissa'] ?>" class="btn btn-sm btn-info">🔍 Ver</a>
                        <a href="export_missas_excel.php" class="btn btn-outline-success">📤 Exportar Excel</a>
                        <a href="export_missas_pdf.php" class="btn btn-outline-danger">📄 Exportar PDF</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">Nenhuma missa encontrada com os filtros informados.</div>
  <?php endif; ?>
</div>
</body>
</html>
