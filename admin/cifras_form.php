<?php
include('../conexao.php');

$idMusica = isset($_GET['idMusica']) ? intval($_GET['idMusica']) : 0;

// Recupera nome da música
$musica = mysqli_fetch_assoc(mysqli_query($conn, "SELECT NomeMusica FROM tbMusica WHERE idMusica = $idMusica"));

// Se foi enviado o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tom = $_POST['TomMusica'];
    $desc = $_POST['DescMusicaCifra'];
    $link = $_POST['LinkSiteCifra'];

    $sql = "INSERT INTO tbcifras (idMusica, TomMusica, DescMusicaCifra, LinkSiteCifra)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'isss', $idMusica, $tom, $desc, $link);
    mysqli_stmt_execute($stmt);

    header("Location: cifras.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Incluir Cifra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h3>Incluir Cifra para: <?= htmlspecialchars($musica['NomeMusica']) ?></h3>
    <form method="POST">
        <div class="mb-3">
            <label for="TomMusica" class="form-label">Tom</label>
            <input type="text" name="TomMusica" id="TomMusica" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="DescMusicaCifra" class="form-label">Descrição da Cifra</label>
            <textarea name="DescMusicaCifra" id="DescMusicaCifra" class="form-control" rows="6" required></textarea>
        </div>
        <div class="mb-3">
            <label for="LinkSiteCifra" class="form-label">Link Externo (opcional)</label>
            <input type="url" name="LinkSiteCifra" id="LinkSiteCifra" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="cifras.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
