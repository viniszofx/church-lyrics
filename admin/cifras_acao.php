<?php
include('../conexao.php');

$idMusica = intval($_GET['idMusica'] ?? 0);
$acao = $_GET['acao'] ?? '';

if ($idMusica <= 0) {
    die("ID da música inválido.");
}

$musica = mysqli_fetch_assoc(mysqli_query($conn, "SELECT NomeMusica FROM tbMusica WHERE idMusica = $idMusica"));

// AÇÃO: EXCLUIR
if ($acao === 'excluir') {
    mysqli_query($conn, "DELETE FROM tbcifras WHERE idMusica = $idMusica");
    header("Location: cifras.php");
    exit;
}

// AÇÃO: SALVAR ALTERAÇÃO (POST vindo do formulário de edição)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'editar') {
    $idCifra = intval($_POST['idCifra']);
    $tom = $_POST['TomMusica'];
    $desc = $_POST['DescMusicaCifra'];
    $link = $_POST['LinkSiteCifra'];

    $sql = "UPDATE tbcifras SET TomMusica=?, DescMusicaCifra=?, LinkSiteCifra=? WHERE idCifra=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssi', $tom, $desc, $link, $idCifra);
    mysqli_stmt_execute($stmt);

    header("Location: cifras_acao.php?acao=editar&idMusica=$idMusica");
    exit;
}

// AÇÃO: VISUALIZAR ou EDITAR (GET)
$cifras = mysqli_query($conn, "SELECT * FROM tbcifras WHERE idMusica = $idMusica");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= ucfirst($acao) ?> Cifras</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h3><?= ucfirst($acao) ?> cifras de: <?= htmlspecialchars($musica['NomeMusica']) ?></h3>

    <?php if ($acao === 'visualizar') { ?>
        <?php while ($linha = mysqli_fetch_assoc($cifras)) { ?>
            <div class="card mb-3">
                <div class="card-header">
                    Tom: <?= htmlspecialchars($linha['TomMusica']) ?>
                </div>
                <div class="card-body">
                    <?php
                         $formatada = htmlspecialchars($linha['DescMusicaCifra']);
                         $formatada = preg_replace('/\\[nota\\](.*?)\\[\\/nota\\]/is', '<strong style="color:blue;">$1</strong>', $formatada);
                         $formatada = preg_replace('/\\[negrito\\](.*?)\\[\\/negrito\\]/is', '<strong>$1</strong>', $formatada);
                         echo '<div style="white-space: pre-wrap; font-family: inherit; clear: both;">' . $formatada . '</div>';
     /*                    <pre><?= htmlspecialchars($linha['DescMusicaCifra']) ?></pre>   */
                      ?>


                    <?php if (!empty($linha['LinkSiteCifra'])): ?>
                        <a href="<?= $linha['LinkSiteCifra'] ?>" target="_blank">Ver site da cifra</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php } ?>

    <?php } elseif ($acao === 'editar') { ?>
        <?php while ($linha = mysqli_fetch_assoc($cifras)) { ?>
            <form method="POST" action="cifras_acao.php?acao=editar&idMusica=<?= $idMusica ?>" class="border p-3 mb-3 rounded bg-light">
                <input type="hidden" name="idCifra" value="<?= $linha['idCifra'] ?>">
                <div class="mb-2">
                    <label>Tom:</label>
                    <input type="text" name="TomMusica" value="<?= htmlspecialchars($linha['TomMusica']) ?>" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Descrição:</label>
                    <textarea name="DescMusicaCifra" class="form-control" rows="10"><?= htmlspecialchars($linha['DescMusicaCifra']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label>Link:</label>
                    <input type="url" name="LinkSiteCifra" value="<?= htmlspecialchars($linha['LinkSiteCifra']) ?>" class="form-control">
                </div>
                <button type="submit" class="btn btn-warning btn-sm">Salvar Alterações</button>
            </form>
        <?php } ?>
    <?php } ?>

    <a href="cifras.php" class="btn btn-secondary mt-3">Voltar</a>
</div>
</body>
</html>
