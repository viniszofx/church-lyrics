<?php
include '../conexao.php';

if (!isset($_GET['id'])) {
    echo "ID da música não especificado.";
    exit;
}

$idMusica = $_GET['id'];

$sql = "SELECT NomeMusica, TomMusica, DescMusicaCifraHtml 
        FROM tbmusica m 
        LEFT JOIN tbcifras c ON m.idMusica = c.idCifra 
        WHERE m.idMusica = $idMusica";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cifra da Música</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2 class="mb-3">Cifra da Música</h2>
    <div class="mb-3"><strong>Nome:</strong> <?= htmlspecialchars($row['NomeMusica']) ?></div>
    <div class="mb-3"><strong>Tom:</strong> <?= htmlspecialchars($row['TomMusica']) ?></div>
    <div class="mb-3"><strong>Cifra:</strong></div>
    <div class="border p-3 bg-light">
        <?= $row['DescMusicaCifraHtml'] ?>
    </div>
    <a href="javascript:history.back()" class="btn btn-secondary mt-3">Voltar</a>
</body>
</html>
