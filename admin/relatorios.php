<?php
include_once('../conexao.php');

// Buscar todos os momentos da missa
$momentos = $conn->query("SELECT idMomento, DescMomento FROM tbMomentosMissa ORDER BY OrdemDeExecucao")->fetch_all(MYSQLI_ASSOC);

// Filtros recebidos por GET
$filtro_musica = $_GET['musica'] ?? '';
$filtro_tom = $_GET['tom'] ?? '';
$filtro_momento = $_GET['momento'] ?? '';

// Consulta principal
$sql = "
SELECT mu.idMusica, mu.NomeMusica, mu.Musica, ci.TomMusica, ci.DescMusicaCifra,
       mo.DescMomento, vi.linkVideo
FROM tbmusica mu
LEFT JOIN tbMusicaMomentoMissa mm ON mm.idMusica = mu.idMusica
LEFT JOIN tbMomentosMissa mo ON mo.idMomento = mm.idMomento
LEFT JOIN tbCifras cm ON cm.idMusica = mu.idMusica
LEFT JOIN tbcifras ci ON ci.idCifra = cm.idCifra
LEFT JOIN tbvideo vi ON vi.idMusica = mu.idMusica
WHERE 1=1
";

if ($filtro_musica) {
    $sql .= " AND mu.NomeMusica LIKE '%$filtro_musica%'";
}
if ($filtro_tom) {
    $sql .= " AND ci.TomMusica LIKE '%$filtro_tom%'";
}
if ($filtro_momento) {
    $sql .= " AND mo.idMomento = '$filtro_momento'";
}

$sql .= " GROUP BY mu.idMusica ORDER BY mu.NomeMusica";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Relatórios e Busca</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .cifra-preview {
      max-height: 80px;
      overflow-y: auto;
      white-space: pre-wrap;
      font-family: monospace;
      font-size: 0.9em;
    }
  </style>
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2 class="mb-4">🎼 Relatórios e Busca</h2>
      <a href="index.php" class="btn btn-secondary btn-sm mb-3">⬅️ Voltar ao menu principal</a>
  <form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
      <input type="text" name="musica" class="form-control" placeholder="Nome da música" value="<?= htmlspecialchars($filtro_musica) ?>">
    </div>
    <div class="col-md-3">
      <input type="text" name="tom" class="form-control" placeholder="Tom da cifra" value="<?= htmlspecialchars($filtro_tom) ?>">
    </div>
    <div class="col-md-3">
      <select name="momento" class="form-select">
        <option value="">Todos os Momentos</option>
        <?php foreach ($momentos as $mom): ?>
          <option value="<?= $mom['idMomento'] ?>" <?= $filtro_momento == $mom['idMomento'] ? 'selected' : '' ?>>
            <?= $mom['DescMomento'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">🔍 Buscar</button>
    </div>
    <div class="col-md-2">
      <a href="relatorios_export_excel.php?<?= http_build_query($_GET) ?>" class="btn btn-success" target="_blank">⬇ Exportar Excel</a>
    </div>
    <div class="col-md-2">
      <a href="relatorios_export_pdf.php?<?= http_build_query($_GET) ?>" class="btn btn-danger mb-3" target="_blank">⬇ Exportar para PDF</a>
    </div>

  </form>

  <?php if ($resultado->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Nome da Música</th>
            <th>Tom</th>
            <th>Momento</th>
            <th>Cifra</th>
            <th>Vídeo</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['NomeMusica']) ?></td>
              <td><?= htmlspecialchars($row['TomMusica'] ?? '-') ?></td>
              <td><?= htmlspecialchars($row['DescMomento'] ?? '-') ?></td>
              <td><div class="cifra-preview"><?= nl2br(htmlspecialchars($row['DescMusicaCifra'] ?? '-')) ?></div></td>
              <td>
                <?php if (!empty($row['linkVideo'])): ?>
                  <a href="<?= $row['linkVideo'] ?>" target="_blank" class="btn btn-sm btn-outline-info">▶ Ver</a>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">Nenhum resultado encontrado com os filtros informados.</div>
  <?php endif; ?>
</div>
</body>
</html>
