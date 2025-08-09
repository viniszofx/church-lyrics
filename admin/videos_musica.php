<?php
include_once('../conexao.php');

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID da música não informado.</div>";
    exit;
}

$idMusica = (int) $_GET['id'];
$resMusica = $conn->query("SELECT NomeMusica FROM tbmusica WHERE idMusica = $idMusica");
if ($resMusica->num_rows == 0) {
    echo "<div class='alert alert-danger'>Música não encontrada.</div>";
    exit;
}
$nomeMusica = $resMusica->fetch_assoc()['NomeMusica'];

if (isset($_POST['inserir']) && !empty($_POST['linkVideo'])) {
    $link = $_POST['linkVideo'];
    $autor = $_POST['autor'];
    $conn->query("INSERT INTO tbvideo (linkVideo, idMusica, autor) VALUES ('$link', $idMusica, '$autor')");
    echo "<div class='alert alert-success'>Vídeo adicionado com sucesso!</div>";
}

if (isset($_GET['excluir'])) {
    $idVideo = (int) $_GET['excluir'];
    $conn->query("DELETE FROM tbvideo WHERE idVideo = $idVideo AND idMusica = $idMusica");
    echo "<div class='alert alert-warning'>Vídeo removido.</div>";
}

$videos = $conn->query("SELECT * FROM tbvideo WHERE idMusica = $idMusica ORDER BY idVideo DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Vídeos da Música</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3 class="mb-3">Vídeos da Música: <strong><?= htmlspecialchars($nomeMusica) ?></strong></h3>

    <form method="post" class="mb-4">
        <div class="mb-3">
            <label class="form-label">Link do vídeo (YouTube, Vimeo etc.)</label>
            <input type="text" name="linkVideo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Autor do Vídeo</label>
            <input type="text" name="autor" class="form-control" placeholder="Nome do autor" required>
        </div>
        <button type="submit" name="inserir" class="btn btn-primary">Adicionar Vídeo</button>
        <a href="musicas_editar.php?id=<?= $idMusica ?>" class="btn btn-secondary">← Voltar para a Música</a> 
    </form>

    <h5>Vídeos Cadastrados</h5>
    <ul class="list-group">
        <?php while ($v = $videos->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <a href="<?= htmlspecialchars($v['linkVideo']) ?>" target="_blank"><?= htmlspecialchars($v['linkVideo']) ?></a>
                    <br><small class="text-muted">Autor: <?= htmlspecialchars($v['Autor']) ?></small>
                </div>
                <a href="?id=<?= $idMusica ?>&excluir=<?= $v['idVideo'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este vídeo?')">Excluir</a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>
