<?php
include '../conexao.php';

if (!isset($_GET['id'])) {
    echo "ID da música não especificado.";
    exit;
}

$idMusica = $_GET['id'];

$sql = "SELECT NomeMusica, LinkVideo FROM tbmusica m 
        LEFT JOIN tbvideo v ON m.idMusica = v.idVideo 
        WHERE m.idMusica = $idMusica";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$link = $row['LinkVideo'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Vídeo da Música</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2 class="mb-3">Vídeo da Música</h2>
    <div class="mb-3"><strong>Nome:</strong> <?= htmlspecialchars($row['NomeMusica']) ?></div>

    <?php if ($link): ?>
        <div class="ratio ratio-16x9">
            <iframe src="<?= htmlspecialchars($link) ?>" title="Vídeo" frameborder="0" allowfullscreen></iframe>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Nenhum vídeo disponível.</div>
    <?php endif; ?>

    <a href="javascript:history.back()" class="btn btn-secondary mt-3">Voltar</a>
</body>
</html>
