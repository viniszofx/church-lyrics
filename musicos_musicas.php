<?php
include "conexao.php";

// Obter a última data da missa
$sql_data = "SELECT MAX(DataMissa) AS UltimaData FROM tbMissa where Status = '1'";
$res_data = $conn->query($sql_data);
$ultimaData = $res_data->fetch_assoc()['UltimaData'] ?? null;

// Buscar todos os momentos da missa ordenados
$sql_momentos = "
                  SELECT mom.idMomento, mom.DescMomento FROM tbMissa m
                    JOIN tbMissaMusicas mm ON mm.idMissa = m.idMissa
                    JOIN tbMusicaMomentoMissa mmm ON mmm.idMusica = mm.idMusica and mmm.idMomento = mm.idMusicaMomentoMissa
                    JOIN tbMomentosMissa mom ON mom.idMomento = mmm.idMomento
                    WHERE m.DataMissa = '$ultimaData' and m.Status = '1'
                    ORDER BY mom.OrdemDeExecucao
                ";
$momentos = $conn->query($sql_momentos)->fetch_all(MYSQLI_ASSOC);

// Buscar todas as músicas da última missa agrupadas por momento
$sql_musicas = "
                  SELECT m.TituloMissa, m.DataMissa, mom.DescMomento, mom.idMomento, mu.Musica, mu.NomeMusica, ci.DescMusicaCifra, ci.TomMusica
                  FROM tbMissa m
                  JOIN tbMissaMusicas mm ON mm.idMissa = m.idMissa
                  JOIN tbMusica mu ON mu.idMusica = mm.idMusica
                  JOIN tbMusicaMomentoMissa mmm ON mmm.idMusica = mu.idMusica and mmm.idMomento = mm.idMusicaMomentoMissa
                  JOIN tbMomentosMissa mom ON mom.idMomento = mmm.idMomento
                  JOIN tbCifras ci ON ci.idMusica = mm.idMusica
                  WHERE m.DataMissa = '$ultimaData'  and m.Status = '1'
                  ORDER BY mom.OrdemDeExecucao
               ";
$res_musicas = $conn->query($sql_musicas);
// Agrupar as músicas por momento
$agrupadas = [];
while ($r = $res_musicas->fetch_assoc()) {
    $id = $r['idMomento'];
    $agrupadas[$id]['DescMomento'] = $r['DescMomento'];
    $agrupadas[$id]['musicas'][] = $r['DescMusicaCifra'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Músicas da Missa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link id="bootstrap-css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" onerror="fallbackBootstrap()">
  <style>
    html { scroll-behavior: smooth; }
    .momento-section { padding-top: 50px; margin-bottom: 30px; }
    .fallback body { font-family: Arial, sans-serif; padding: 1em; }
    .fallback h4 { color: #444; margin-top: 1em; }
    .fallback .link-momentos a { display: inline-block; margin: 4px; padding: 6px 10px; background: #ddd; text-decoration: none; color: #000; }
    .fallback ul { padding-left: 1.5em; }
    .fallback li { margin-bottom: 0.5em; }
  </style>
  <script>
    function fallbackBootstrap() {
      document.documentElement.classList.add('fallback');
      const css = document.getElementById('bootstrap-css');
      if (css) css.remove();
    }
  </script>
</head>
<body class="bg-light" id="topo">
<div class="container-fluid p-4">
  <div class="text-center mb-4">
    <h2 class="mb-3">🎼 Músicas da Missa</h2>
    <p class="text-muted">Data: <?= $ultimaData ? date('d/m/Y', strtotime($ultimaData)) : "Não encontrada" ?></p>
  </div>

  <?php if ($ultimaData): ?>
    <!-- Links para Momentos -->
      <div class="link-momentos mb-4 text-center">
          <div class="d-flex flex-wrap justify-content-center gap-2">
              <?php foreach ($momentos as $mom): ?>
                <a href="#momento<?= $mom['idMomento'] ?>" class="btn btn-outline-primary btn-sm"><?= $mom['DescMomento'] ?></a>
              <?php endforeach; ?>
          </div>
      </div>

     <!-- Botão voltar -->
      <div class="text-center mb-4">
          <a href="index.php" class="btn btn-outline-danger">← Voltar para a página inicial</a>
      </div>
    <!-- Listagem de Músicas -->
    <?php foreach ($momentos as $mom): ?>
      <?php if (isset($agrupadas[$mom['idMomento']])): ?>
        <div id="momento<?= $mom['idMomento'] ?>" class="momento-section">
          <h4 class="text-primary"><?= $mom['DescMomento'] ?></h4>
          <ul class="list-group">
            <?php foreach ($agrupadas[$mom['idMomento']]['musicas'] as $musica): ?>
                <li class="list-group-item">
<!--                  <a href="#topo" class="btn btn-sm btn-outline-secondary float-end mb-2">↑ Topo</a>    -->
                  <a href="#topo" class="btn btn-sm btn-outline-danger float-end mb-2">↑ Topo</a>
                     <?php
                         $formatada = htmlspecialchars($musica);
                         $formatada = preg_replace('/\\[nota\\](.*?)\\[\\/nota\\]/is', '<strong style="color:blue;">$1</strong>', $formatada);
                         $formatada = preg_replace('/\\[negrito\\](.*?)\\[\\/negrito\\]/is', '<strong>$1</strong>', $formatada);
                         echo '<div style="white-space: pre-wrap; font-family: inherit; clear: both;">' . $formatada . '</div>';
                      ?>
                </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-warning text-center">Nenhuma missa encontrada no banco de dados.</div>
  <?php endif; ?>


</div>
</body>
</html>
