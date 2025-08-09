<?php
include_once('../conexao.php');

$filtro = $_GET['busca'] ?? '';

// Buscar músicas com filtro
$sql_musicas = "SELECT idMusica, NomeMusica FROM tbmusica";
if ($filtro) {
    $sql_musicas .= " WHERE NomeMusica LIKE '%$filtro%'";
}
$sql_musicas .= " ORDER BY NomeMusica";
$musicas = $conn->query($sql_musicas)->fetch_all(MYSQLI_ASSOC);

// Inserir vídeo
if (isset($_POST['inserir']) && isset($_POST['idMusica'])) {
    $idMusica = (int) $_POST['idMusica'];
    $link = $_POST['linkVideo'];
    $autor = $_POST['Autor'];
    $conn->query("INSERT INTO tbvideo (linkVideo, idMusica, Autor) VALUES ('$link', $idMusica, '$autor')");
    echo "<div class='alert alert-success'>Vídeo adicionado com sucesso!</div>";
}

// Excluir vídeo
if (isset($_GET['excluir']) && isset($_GET['musica_id'])) {
    $idVideo = (int) $_GET['excluir'];
    $idMusica = (int) $_GET['musica_id'];
    $conn->query("DELETE FROM tbvideo WHERE idVideo = $idVideo AND idMusica = $idMusica");
    echo "<div class='alert alert-warning mt-3'>Vídeo removido com sucesso.</div>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Vídeos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="mb-3">Cadastro de Vídeos</h2>

    <a href="index.php" class="btn btn-secondary btn-sm mb-3">⬅️ Voltar ao menu principal</a>


    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="busca" class="form-control" placeholder="Buscar música..." value="<?= htmlspecialchars($filtro) ?>">
            <button type="submit" class="btn btn-primary">🔍 Pesquisar</button>
        </div>
    </form>

    <?php if (isset($_GET['musica_id'])): 
        $idMusica = (int) $_GET['musica_id'];
        $mus = $conn->query("SELECT idMusica, NomeMusica FROM tbmusica WHERE idMusica = $idMusica")->fetch_assoc();
    ?>

    <h5>Música Selecionada: <strong><?= $mus['NomeMusica'] ?></strong></h5>
    <form method="post" class="mb-4">
        <input type="hidden" name="idMusica" value="<?= $idMusica ?>">
        <div class="mb-3">
            <label class="form-label">Link do Vídeo</label>
            <input type="text" name="linkVideo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Autor</label>
            <input type="text" name="autor" class="form-control" required>
        </div>
        <button type="submit" name="inserir" class="btn btn-success">Salvar</button>
        <a href="videos.php?busca=<?= urlencode($filtro) ?>" class="btn btn-secondary">← Cancelar</a>
    </form>

    <h5>Vídeos Cadastrados</h5>
    <ul class="list-group mb-5">
        <?php
        $videos = $conn->query("SELECT * FROM tbvideo WHERE idMusica = $idMusica ORDER BY idVideo DESC");
        while ($v = $videos->fetch_assoc()):
        ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <a href="<?= htmlspecialchars($v['linkVideo']) ?>" target="_blank"><?= htmlspecialchars($v['linkVideo']) ?></a>
                    <br><small class="text-muted">Autor: <?= htmlspecialchars($v['Autor']) ?></small>
                </div>
                <a href="?busca=<?= urlencode($filtro) ?>&musica_id=<?= $idMusica ?>&excluir=<?= $v['idVideo'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este vídeo?')">Excluir</a>
            </li>
        <?php endwhile; ?>
    </ul>

    <?php else: ?>

    <h5>Selecione uma música para vincular vídeos:</h5>
    <ul class="list-group">
        <?php foreach ($musicas as $mus): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $mus['NomeMusica'] ?>
                <a href="?busca=<?= urlencode($filtro) ?>&musica_id=<?= $mus['idMusica'] ?>" class="btn btn-sm btn-outline-primary">Selecionar</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php endif; ?>
</div>
</body>
</html>
