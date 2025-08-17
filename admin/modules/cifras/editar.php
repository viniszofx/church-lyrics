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

// Verificar se um ID específico de cifra foi fornecido
$idCifra = isset($_GET['idCifra']) ? intval($_GET['idCifra']) : null;

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

// Processar formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cifraId = intval($_POST['idCifra']);
    $tom = mysqli_real_escape_string($conn, $_POST['TomMusica']);
    $desc = mysqli_real_escape_string($conn, $_POST['DescMusicaCifra']);
    $link = mysqli_real_escape_string($conn, $_POST['LinkSiteCifra']);

    $sql = "UPDATE tbcifras SET TomMusica = ?, DescMusicaCifra = ?, LinkSiteCifra = ? WHERE idCifra = ? AND idMusica = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param('sssii', $tom, $desc, $link, $cifraId, $idMusica);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Cifra atualizada com sucesso!</div>';
            // Decidir para onde redirecionar com base em se foi um ID de cifra específico
            if ($idCifra) {
                echo '<script>setTimeout(function() { window.location.href = "visualizar.php?idMusica=' . $idMusica . '"; }, 2000);</script>';
            } else {
                echo '<script>setTimeout(function() { window.location.href = "editar.php?idMusica=' . $idMusica . '"; }, 2000);</script>';
            }
        } else {
            echo '<div class="alert alert-danger">Erro ao atualizar: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Busca as cifras da música
$sql = "SELECT * FROM tbcifras WHERE idMusica = ?";
// Se fornecido um ID de cifra específico, filtrar apenas para esse
if ($idCifra) {
    $sql .= " AND idCifra = ?";
}
$sql .= " ORDER BY TomMusica";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    include_once('../../includes/footer.php');
    exit;
}

if ($idCifra) {
    $stmt->bind_param('ii', $idMusica, $idCifra);
} else {
    $stmt->bind_param('i', $idMusica);
}

$stmt->execute();
$cifras = $stmt->get_result();
$stmt->close();

// Verificar se encontrou cifras para editar
if ($cifras->num_rows == 0) {
    echo '<div class="alert alert-danger">Nenhuma cifra encontrada para esta música.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    include_once('../../includes/footer.php');
    exit;
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Cifras</h2>
        <div>
            <a href="visualizar.php?idMusica=<?= $idMusica ?>" class="btn btn-info me-2">Visualizar Cifras</a>
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

    <?php while ($linha = $cifras->fetch_assoc()): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Editar Cifra - Tom: <?= htmlspecialchars($linha['TomMusica']) ?></h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="idCifra" value="<?= $linha['idCifra'] ?>">
                    
                    <div class="mb-3">
                        <label for="TomMusica_<?= $linha['idCifra'] ?>" class="form-label">Tom</label>
                        <input type="text" name="TomMusica" id="TomMusica_<?= $linha['idCifra'] ?>" 
                               class="form-control" value="<?= htmlspecialchars($linha['TomMusica']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="DescMusicaCifra_<?= $linha['idCifra'] ?>" class="form-label">Descrição da Cifra</label>
                        <textarea name="DescMusicaCifra" id="DescMusicaCifra_<?= $linha['idCifra'] ?>" 
                                  class="form-control" rows="12"><?= htmlspecialchars($linha['DescMusicaCifra']) ?></textarea>
                        <small class="text-muted">
                            Dicas de formatação:<br>
                            - Use [nota]G[/nota] para destacar notas<br>
                            - Use [negrito]texto[/negrito] para textos em negrito
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="LinkSiteCifra_<?= $linha['idCifra'] ?>" class="form-label">Link Externo (opcional)</label>
                        <input type="url" name="LinkSiteCifra" id="LinkSiteCifra_<?= $linha['idCifra'] ?>" 
                               class="form-control" value="<?= htmlspecialchars($linha['LinkSiteCifra']) ?>">
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Salvar Alterações</button>
                        <a href="<?= $idCifra ? "visualizar.php?idMusica={$idMusica}" : "editar.php?idMusica={$idMusica}" ?>" 
                           class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include_once('../../includes/footer.php'); ?>
