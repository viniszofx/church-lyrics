<?php
include_once('../conexao.php');

$id = $_GET['id'] ?? 0;

$sql = "SELECT mu.idMusica, mu.NomeMusica, mu.Musica, mm.DescMomento, mm.OrdemDeExecucao, 
               v.LinkVideo, c.TomMusica, c.DescMusicaCifra, c.DescMusicaCifraHtml,
               tl.DescTempo, tl.Sigla
        FROM tbMusica mu
        LEFT JOIN tbmusicamomentomissa mmm ON mu.IdMusica = mmm.idMusica
        LEFT JOIN tbmomentosmissa mm ON mmm.idMomento = mm.idMomento
        LEFT JOIN tbvideo v ON mu.idMusica = v.idVideo
        LEFT JOIN tbcifras c ON mu.idMusica = c.idCifra
        LEFT JOIN tbtempoMusica tm ON mu.idMusica = tm.idMusica
        LEFT JOIN tbtpliturgico tl ON tm.idTpLiturgico = tl.idTpLiturgico
        WHERE mu.idMusica = $id
        ORDER BY mm.OrdemDeExecucao
        LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Formatando o texto da música com negrito
      $musicaFormatada = htmlspecialchars($row['Musica']);
      $musicaFormatada = preg_replace('/\\[negrito\\](.*?)\\[\\/negrito\\]/is', '<strong>$1</strong>', $musicaFormatada);
} else {
    echo "Música não encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Música</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>Visualização da Música</h2>
    <a href="musicas.php" class="btn btn-secondary ml-2">Voltar Para o Menu Anterior</a>

    <div class="card">
        <div class="card-body">

            <p><strong>ID:</strong> <?= $row['idMusica'] ?></p>
            <p><strong>Nome da Música:</strong> <?= htmlspecialchars($row['NomeMusica']) ?></p>
            <p><strong>Momento da Missa:</strong> <?= htmlspecialchars($row['DescMomento']) ?></p>

            <hr>
            <h5>Música</h5>
            <div style="white-space: pre-wrap; font-family: inherit;"><?= $musicaFormatada ?></div>
            <hr>

            <a href="visualizar_cifra.php?id=<?= $row['idMusica'] ?>" class="btn btn-outline-primary">Visualizar Cifra</a>
            <a href="visualizar_video.php?id=<?= $row['idMusica'] ?>" class="btn btn-outline-secondary">Visualizar Vídeos</a>
            <a href="musicas.php" class="btn btn-secondary ml-2">Voltar Para o Menu Anterior</a>

        </div>
    </div>

</body>
</html>
