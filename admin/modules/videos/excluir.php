<?php
include_once('../../includes/header.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="alert alert-danger">ID não fornecido.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    include_once('../../includes/footer.php');
    exit;
}

$id = intval($_GET['id']);

// Se foi enviada a confirmação para excluir
if (isset($_POST['confirmar_exclusao'])) {
    $sql = "DELETE FROM tbvideo WHERE idVideo = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Vídeo excluído com sucesso!</div>';
            echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 2000);</script>';
        } else {
            echo '<div class="alert alert-danger">Erro ao excluir vídeo: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
} else {
    // Buscar informações do vídeo para exibir na confirmação
    $sql = "SELECT v.idVideo, v.descricaoVideo, m.NomeMusica
            FROM tbvideo v
            JOIN tbmusica m ON v.idMusica = m.idMusica
            WHERE v.idVideo = ?";
            
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
        include_once('../../includes/footer.php');
        exit;
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result || $result->num_rows == 0) {
        echo '<div class="alert alert-danger">Vídeo não encontrado.</div>';
        echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
        $stmt->close();
        include_once('../../includes/footer.php');
        exit;
    }
    
    $video = $result->fetch_assoc();
    $stmt->close();
    
    // Buscar o link do vídeo para exibir pré-visualização
    $sql_link = "SELECT linkVideo FROM tbvideo WHERE idVideo = ?";
    $stmt_link = $conn->prepare($sql_link);
    $stmt_link->bind_param("i", $id);
    $stmt_link->execute();
    $result_link = $stmt_link->get_result();
    $link_info = $result_link->fetch_assoc();
    $youtubeId = $link_info['linkVideo'];
    $stmt_link->close();
    
    // Extrair ID do YouTube se for um link completo
    if (strpos($youtubeId, 'youtube.com/watch?v=') !== false) {
        parse_str(parse_url($youtubeId, PHP_URL_QUERY), $params);
        if (isset($params['v'])) {
            $youtubeId = $params['v'];
        }
    } elseif (strpos($youtubeId, 'youtu.be/') !== false) {
        $youtubeId = substr(parse_url($youtubeId, PHP_URL_PATH), 1);
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Excluir Vídeo</h2>
        <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Confirmar Exclusão</h5>
                </div>
                <div class="card-body">
                    <p>Tem certeza que deseja excluir o vídeo:</p>
                    
                    <div class="alert alert-warning">
                        <p><strong>Música:</strong> <?= htmlspecialchars($video['NomeMusica']) ?></p>
                        <?php if (!empty($video['descricaoVideo'])): ?>
                        <p><strong>Descrição:</strong> <?= htmlspecialchars($video['descricaoVideo']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <p class="text-danger">Esta ação não pode ser desfeita.</p>
                    
                    <form method="post">
                        <div class="d-flex gap-2">
                            <button type="submit" name="confirmar_exclusao" class="btn btn-danger">
                                Sim, excluir vídeo
                            </button>
                            <a href="list.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pré-visualização</h5>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($youtubeId) ?>" 
                                title="YouTube video player" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
