<?php
include_once('../../includes/header.php');

if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">ID do vídeo não especificado.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    include_once('../../includes/footer.php');
    exit;
}

$idVideo = intval($_GET['id']);

$sql = "SELECT v.idVideo, v.linkVideo, v.Autor, m.idMusica, m.NomeMusica 
        FROM tbvideo v
        JOIN tbmusica m ON v.idMusica = m.idMusica
        WHERE v.idVideo = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    include_once('../../includes/footer.php');
    exit;
}

$stmt->bind_param("i", $idVideo);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    echo '<div class="alert alert-danger">Vídeo não encontrado.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    $stmt->close();
    include_once('../../includes/footer.php');
    exit;
}

$row = $result->fetch_assoc();
$stmt->close();

// Função para obter ID do YouTube a partir da URL
function getYoutubeEmbedUrl($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
    preg_match($pattern, $url, $matches);
    
    if (isset($matches[1])) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }
    
    // Se não for YouTube, retorna a URL original
    return $url;
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Visualizar Vídeo</h2>
        <div>
            <a href="list.php?musica_id=<?= $row['idMusica'] ?>" class="btn btn-secondary">Voltar à lista</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Música: <?= htmlspecialchars($row['NomeMusica']) ?></h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Autor do Vídeo:</strong> <?= htmlspecialchars($row['Autor']) ?>
            </div>
            <div class="mb-3">
                <strong>Link Original:</strong> 
                <a href="<?= htmlspecialchars($row['linkVideo']) ?>" target="_blank"><?= htmlspecialchars($row['linkVideo']) ?></a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Player de Vídeo</h5>
        </div>
        <div class="card-body">
            <?php
            $embedUrl = getYoutubeEmbedUrl($row['linkVideo']);
            ?>
            <div class="ratio ratio-16x9">
                <iframe src="<?= htmlspecialchars($embedUrl) ?>" 
                        title="<?= htmlspecialchars($row['NomeMusica']) ?>" 
                        allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
