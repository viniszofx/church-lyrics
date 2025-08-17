<?php
include_once('../../includes/header.php');

$idVideo = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensagem = '';
$video = null;

// Se não tiver ID, redireciona para a lista
if ($idVideo <= 0) {
    echo '<div class="alert alert-danger">ID de vídeo inválido.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    include_once('../../includes/footer.php');
    exit;
}

// Buscar dados do vídeo
$sql = "SELECT v.*, m.NomeMusica 
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

$video = $result->fetch_assoc();
$stmt->close();

// Listar todas as músicas para o dropdown
$sql_musicas = "SELECT idMusica, NomeMusica FROM tbmusica ORDER BY NomeMusica";
$result_musicas = $conn->query($sql_musicas);

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMusica = isset($_POST['idMusica']) ? intval($_POST['idMusica']) : 0;
    $descricaoVideo = isset($_POST['descricaoVideo']) ? trim($_POST['descricaoVideo']) : '';
    $linkVideo = isset($_POST['linkVideo']) ? trim($_POST['linkVideo']) : '';
    
    // Validações
    if ($idMusica <= 0) {
        $mensagem = '<div class="alert alert-danger">Selecione uma música válida.</div>';
    } elseif (empty($linkVideo)) {
        $mensagem = '<div class="alert alert-danger">O link do vídeo é obrigatório.</div>';
    } else {
        // Extrair o ID do vídeo do YouTube se for um link completo
        if (strpos($linkVideo, 'youtube.com/watch?v=') !== false) {
            parse_str(parse_url($linkVideo, PHP_URL_QUERY), $params);
            if (isset($params['v'])) {
                $linkVideo = $params['v'];
            }
        } elseif (strpos($linkVideo, 'youtu.be/') !== false) {
            $linkVideo = substr(parse_url($linkVideo, PHP_URL_PATH), 1);
        }
        
        // Atualiza o vídeo
        $sql_update = "UPDATE tbvideo SET 
                      idMusica = ?, 
                      descricaoVideo = ?, 
                      linkVideo = ? 
                      WHERE idVideo = ?";
                      
        $stmt_update = $conn->prepare($sql_update);
        
        if (!$stmt_update) {
            $mensagem = '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
        } else {
            $stmt_update->bind_param("issi", $idMusica, $descricaoVideo, $linkVideo, $idVideo);
            
            if ($stmt_update->execute()) {
                $mensagem = '<div class="alert alert-success">Vídeo atualizado com sucesso!</div>';
                
                // Recarregar dados do vídeo após atualização
                $sql = "SELECT v.*, m.NomeMusica 
                        FROM tbvideo v
                        JOIN tbmusica m ON v.idMusica = m.idMusica
                        WHERE v.idVideo = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $idVideo);
                $stmt->execute();
                $result = $stmt->get_result();
                $video = $result->fetch_assoc();
                $stmt->close();
            } else {
                $mensagem = '<div class="alert alert-danger">Erro ao atualizar vídeo: ' . $stmt_update->error . '</div>';
            }
            
            $stmt_update->close();
        }
    }
}

// Extrair o ID do vídeo para pré-visualização
$youtubeId = $video['linkVideo'];
// Se for um link completo, extrair o ID
if (strpos($youtubeId, 'youtube.com/watch?v=') !== false) {
    parse_str(parse_url($youtubeId, PHP_URL_QUERY), $params);
    if (isset($params['v'])) {
        $youtubeId = $params['v'];
    }
} elseif (strpos($youtubeId, 'youtu.be/') !== false) {
    $youtubeId = substr(parse_url($youtubeId, PHP_URL_PATH), 1);
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Vídeo</h2>
        <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
    </div>
    
    <?php echo $mensagem; ?>
    
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="idMusica" class="form-label">Música</label>
                            <select class="form-select" id="idMusica" name="idMusica" required>
                                <option value="">Selecione uma música</option>
                                <?php
                                if ($result_musicas) {
                                    while ($musica = $result_musicas->fetch_assoc()) {
                                        $selected = ($musica['idMusica'] == $video['idMusica']) ? 'selected' : '';
                                        echo '<option value="' . $musica['idMusica'] . '" ' . $selected . '>' . 
                                             htmlspecialchars($musica['NomeMusica']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descricaoVideo" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricaoVideo" name="descricaoVideo" 
                                   value="<?= htmlspecialchars($video['descricaoVideo'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="linkVideo" class="form-label">Link do Vídeo (YouTube)</label>
                            <input type="text" class="form-control" id="linkVideo" name="linkVideo" 
                                   value="<?= htmlspecialchars($video['linkVideo']) ?>" required>
                            <div class="form-text text-muted">
                                Pode ser o link completo (https://www.youtube.com/watch?v=XXXX) ou apenas o ID do vídeo
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            <a href="list.php" class="btn btn-secondary ms-2">Cancelar</a>
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
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($youtubeId) ?>" 
                                title="YouTube video player" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen></iframe>
                    </div>
                    <p class="fw-bold mb-1"><?= htmlspecialchars($video['NomeMusica']) ?></p>
                    <p class="text-muted"><?= htmlspecialchars($video['descricaoVideo'] ?? '') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
