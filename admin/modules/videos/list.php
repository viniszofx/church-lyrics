<?php
include_once('../../includes/header.php');

$filtro = $_GET['busca'] ?? '';

// Buscar músicas com filtro
$sql_musicas = "SELECT idMusica, NomeMusica FROM tbmusica";
if ($filtro) {
    $filtro = mysqli_real_escape_string($conn, $filtro);
    $sql_musicas .= " WHERE NomeMusica LIKE '%$filtro%'";
}
$sql_musicas .= " ORDER BY NomeMusica";
$stmt = $conn->prepare($sql_musicas);

if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
} else {
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        echo '<div class="alert alert-danger">Erro ao buscar músicas: ' . $conn->error . '</div>';
        $musicas = [];
    } else {
        $musicas = $result->fetch_all(MYSQLI_ASSOC);
    }
    
    $stmt->close();
}

// Inserir vídeo
if (isset($_POST['inserir']) && isset($_POST['idMusica'])) {
    $idMusica = intval($_POST['idMusica']);
    $link = mysqli_real_escape_string($conn, $_POST['linkVideo']);
    $autor = mysqli_real_escape_string($conn, $_POST['autor']);
    
    $sql = "INSERT INTO tbvideo (linkVideo, idMusica, Autor) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da inserção: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param("sis", $link, $idMusica, $autor);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Vídeo adicionado com sucesso!</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao inserir vídeo: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Excluir vídeo
if (isset($_GET['excluir']) && isset($_GET['musica_id'])) {
    $idVideo = intval($_GET['excluir']);
    $idMusica = intval($_GET['musica_id']);
    
    $sql = "DELETE FROM tbvideo WHERE idVideo = ? AND idMusica = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da exclusão: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param("ii", $idVideo, $idMusica);
        if ($stmt->execute()) {
            echo '<div class="alert alert-warning mt-3">Vídeo removido com sucesso.</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao excluir vídeo: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Cadastro de Vídeos</h2>
        <a href="../../index.php" class="btn btn-secondary">Voltar ao menu principal</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Buscar Música</h5>
            <form method="get" class="mb-3">
                <div class="input-group">
                    <input type="text" name="busca" class="form-control" placeholder="Buscar música..." value="<?= htmlspecialchars($filtro) ?>">
                    <button type="submit" class="btn btn-primary">🔍 Pesquisar</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($_GET['musica_id'])): 
        $idMusica = intval($_GET['musica_id']);
        
        $sql = "SELECT idMusica, NomeMusica FROM tbmusica WHERE idMusica = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
        } else {
            $stmt->bind_param("i", $idMusica);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if (!$result || $result->num_rows == 0) {
                echo '<div class="alert alert-danger">Música não encontrada.</div>';
            } else {
                $mus = $result->fetch_assoc();
    ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Música Selecionada: <?= htmlspecialchars($mus['NomeMusica']) ?></h5>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="idMusica" value="<?= $idMusica ?>">
                <div class="mb-3">
                    <label class="form-label">Link do Vídeo</label>
                    <input type="text" name="linkVideo" class="form-control" required>
                    <small class="text-muted">Cole aqui o link do YouTube, Vimeo ou outra plataforma</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Autor</label>
                    <input type="text" name="autor" class="form-control" required>
                    <small class="text-muted">Nome do autor ou criador do vídeo</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="inserir" class="btn btn-success">Salvar</button>
                    <a href="list.php?busca=<?= urlencode($filtro) ?>" class="btn btn-secondary">← Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Vídeos Cadastrados</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <?php
                $sql = "SELECT * FROM tbvideo WHERE idMusica = ? ORDER BY idVideo DESC";
                $stmt = $conn->prepare($sql);
                
                if (!$stmt) {
                    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
                } else {
                    $stmt->bind_param("i", $idMusica);
                    $stmt->execute();
                    $videos = $stmt->get_result();
                    
                    if (!$videos) {
                        echo '<div class="alert alert-danger">Erro ao buscar vídeos: ' . $conn->error . '</div>';
                    } else if ($videos->num_rows == 0) {
                        echo '<li class="list-group-item">Nenhum vídeo cadastrado para esta música.</li>';
                    } else {
                        while ($v = $videos->fetch_assoc()):
                ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="<?= htmlspecialchars($v['linkVideo']) ?>" target="_blank" class="text-primary">
                                <i class="bi bi-link"></i> <?= htmlspecialchars($v['linkVideo']) ?>
                            </a>
                            <br><small class="text-muted">Autor: <?= htmlspecialchars($v['Autor']) ?></small>
                        </div>
                        <div>
                            <a href="visualizar.php?id=<?= $v['idVideo'] ?>" class="btn btn-sm btn-info me-2">
                                <i class="bi bi-eye"></i> Visualizar
                            </a>
                            <a href="?busca=<?= urlencode($filtro) ?>&musica_id=<?= $idMusica ?>&excluir=<?= $v['idVideo'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Tem certeza que deseja excluir este vídeo?')">
                                <i class="bi bi-trash"></i> Excluir
                            </a>
                        </div>
                    </li>
                <?php 
                        endwhile;
                    }
                    $stmt->close();
                }
                ?>
            </ul>
        </div>
    </div>

    <?php 
            }
            // Removed duplicate $stmt->close() that was causing the error
        }
    else: 
    ?>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Selecione uma música para vincular vídeos</h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                <?php 
                if (empty($musicas)) {
                    echo '<div class="alert alert-info">Nenhuma música encontrada com os critérios de busca.</div>';
                } else {
                    foreach ($musicas as $mus): 
                ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($mus['NomeMusica']) ?>
                        <a href="?busca=<?= urlencode($filtro) ?>&musica_id=<?= $mus['idMusica'] ?>" 
                           class="btn btn-sm btn-outline-primary">
                            Selecionar
                        </a>
                    </div>
                <?php 
                    endforeach;
                }
                ?>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<?php include_once('../../includes/footer.php'); ?>
