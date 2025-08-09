<?php
include('../conexao.php');

if (!isset($_GET['id'])) {
    echo "ID da música não especificado.";
    exit;
}

$idMusica = intval($_GET['id']);

// Buscar dados da música
$sql = "SELECT * FROM tbmusica WHERE idMusica = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idMusica);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Música não encontrada.";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Música</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Editar Música</h3>
        <div>
            <a href="musicas.php" class="btn btn-secondary">Voltar para a Lista</a>
        </div>
    </div>

    <form action="musicas_salvar.php" method="post">
        <input type="hidden" name="idMusica" value="<?php echo $row['idMusica']; ?>">

        <div class="mb-3">
            <label for="NomeMusica" class="form-label">Nome da Música</label>
            <input type="text" name="NomeMusica" id="NomeMusica" class="form-control" value="<?php echo htmlspecialchars($row['NomeMusica']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="Musica" class="form-label">Texto da Música</label>
            <textarea name="Musica" id="Musica" rows="8" class="form-control" required><?php echo htmlspecialchars($row['Musica']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="musicas.php" class="btn btn-danger">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>