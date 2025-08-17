<?php
include_once('../../includes/header.php');

// Verificar se o ID da música foi fornecido
if (!isset($_GET['idMusica']) || empty($_GET['idMusica'])) {
    echo '<div class="alert alert-danger">ID da música não fornecido.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    include_once('../../includes/footer.php');
    exit;
}

$idMusica = intval($_GET['idMusica']);

// Recupera nome da música
$sql = "SELECT NomeMusica FROM tbmusica WHERE idMusica = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    include_once('../../includes/footer.php');
    exit;
}

$stmt->bind_param('i', $idMusica);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<div class="alert alert-danger">Música não encontrada.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    $stmt->close();
    include_once('../../includes/footer.php');
    exit;
}

$musica = $result->fetch_assoc();
$stmt->close();

// Busca as cifras da música
$sql = "SELECT * FROM tbcifras WHERE idMusica = ? ORDER BY TomMusica";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    include_once('../../includes/footer.php');
    exit;
}

$stmt->bind_param('i', $idMusica);
$stmt->execute();
$cifras = $stmt->get_result();
$stmt->close();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Visualizar Cifras</h2>
        <div>
            <a href="form.php?idMusica=<?= $idMusica ?>" class="btn btn-success me-2">Adicionar Cifra</a>
            <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
                Música: <?= htmlspecialchars($musica['NomeMusica']) ?>
            </h5>
        </div>
    </div>

    <?php if ($cifras->num_rows == 0): ?>
        <div class="alert alert-info">
            Nenhuma cifra cadastrada para esta música.
        </div>
    <?php else: ?>
        <div class="row">
            <?php while ($linha = $cifras->fetch_assoc()): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Tom: <?= htmlspecialchars($linha['TomMusica']) ?></h5>
                            <div>
                                <a href="editar.php?idMusica=<?= $idMusica ?>&idCifra=<?= $linha['idCifra'] ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                                $formatada = htmlspecialchars($linha['DescMusicaCifra']);
                                $formatada = preg_replace('/\\[nota\\](.*?)\\[\\/nota\\]/is', '<strong style="color:blue;">$1</strong>', $formatada);
                                $formatada = preg_replace('/\\[negrito\\](.*?)\\[\\/negrito\\]/is', '<strong>$1</strong>', $formatada);
                                echo '<div style="white-space: pre-wrap; font-family: monospace; clear: both;">' . $formatada . '</div>';
                            ?>
                        </div>
                        <?php if (!empty($linha['LinkSiteCifra'])): ?>
                            <div class="card-footer">
                                <a href="<?= htmlspecialchars($linha['LinkSiteCifra']) ?>" 
                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-link-45deg"></i> Ver site da cifra
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php include_once('../../includes/footer.php'); ?>
