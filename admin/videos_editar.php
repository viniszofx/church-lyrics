<?php
include_once('../conexao.php');
if (!isset($_GET['id'])) {
    header("Location: videos.php");
    exit;
}
$id = $_GET['id'];
$res = $conn->query("SELECT * FROM tbvideo WHERE idVideo = $id");
if ($res->num_rows == 0) {
    echo "<div class='alert alert-danger'>Registro não encontrado.</div>";
    exit;
}
$row = $res->fetch_assoc();

$musicas = $conn->query("SELECT idMusica, NomeMusica FROM tbmusica ORDER BY NomeMusica")->fetch_all(MYSQLI_ASSOC);
$res_vinc = $conn->query("SELECT idMusica FROM tbVideo WHERE idVideo = $id");
$vinculados = [];
while ($r = $res_vinc->fetch_assoc()) {
    $vinculados[] = $r['idMusica'];
}

if (isset($_POST['salvar'])) {
    $linkVideo = $_POST['linkVideo'];
    $conn->query("UPDATE tbvideo SET linkVideo='$linkVideo' WHERE idVideo = $id");

    $conn->query("DELETE FROM tbVideoWHERE idVideo = $id");
    if (!empty($_POST['musicas'])) {
        foreach ($_POST['musicas'] as $idMusica) {
            $conn->query("INSERT INTO tbVideo (idVideo, idMusica) VALUES ($id, $idMusica)");
        }
    }

    header("Location: videos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Vídeo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Editar Vídeo</h2>
    <form method="post" class="border p-3">
        <div class="mb-3">
            <label class="form-label">Link do Vídeo</label>
            <input type="text" name="linkVideo" class="form-control" value="<?= $row['linkVideo'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Músicas Relacionadas</label><br>
            <?php foreach ($musicas as $mus): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="musicas[]" value="<?= $mus['idMusica'] ?>"
                        <?= in_array($mus['idMusica'], $vinculados) ? 'checked' : '' ?>
                        id="mus<?= $mus['idMusica'] ?>">
                    <label class="form-check-label" for="mus<?= $mus['idMusica'] ?>"><?= $mus['NomeMusica'] ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
        <a href="videos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
