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

// Verificar se um ID específico de cifra foi fornecido (para excluir apenas uma cifra)
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

// Processar a exclusão
if (isset($_POST['confirmar_exclusao'])) {
    if ($idCifra) {
        // Excluir apenas uma cifra específica
        $sql = "DELETE FROM tbcifras WHERE idCifra = ? AND idMusica = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
        } else {
            $stmt->bind_param('ii', $idCifra, $idMusica);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Cifra excluída com sucesso!</div>';
                echo '<script>setTimeout(function() { window.location.href = "visualizar.php?idMusica=' . $idMusica . '"; }, 2000);</script>';
            } else {
                echo '<div class="alert alert-danger">Erro ao excluir: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    } else {
        // Excluir todas as cifras da música
        $sql = "DELETE FROM tbcifras WHERE idMusica = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
        } else {
            $stmt->bind_param('i', $idMusica);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Todas as cifras foram excluídas com sucesso!</div>';
                echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 2000);</script>';
            } else {
                echo '<div class="alert alert-danger">Erro ao excluir: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }
} else {
    // Se o idCifra foi fornecido, busca informações dessa cifra específica
    if ($idCifra) {
        $sql = "SELECT TomMusica FROM tbcifras WHERE idCifra = ? AND idMusica = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
            include_once('../../includes/footer.php');
            exit;
        }
        
        $stmt->bind_param('ii', $idCifra, $idMusica);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            echo '<div class="alert alert-danger">Cifra não encontrada.</div>';
            echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
            $stmt->close();
            include_once('../../includes/footer.php');
            exit;
        }
        
        $cifra = $result->fetch_assoc();
        $stmt->close();
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Excluir Cifras</h2>
        <a href="<?= $idCifra ? "visualizar.php?idMusica={$idMusica}" : "list.php" ?>" class="btn btn-secondary">Cancelar</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Confirmar Exclusão</h5>
        </div>
        <div class="card-body">
            <p>
                <?php if ($idCifra): ?>
                    Tem certeza que deseja excluir a cifra no tom <strong><?= htmlspecialchars($cifra['TomMusica']) ?></strong> 
                    da música <strong><?= htmlspecialchars($musica['NomeMusica']) ?></strong>?
                <?php else: ?>
                    Tem certeza que deseja excluir <strong>todas as cifras</strong> 
                    da música <strong><?= htmlspecialchars($musica['NomeMusica']) ?></strong>?
                <?php endif; ?>
            </p>
            <p class="text-danger">Esta ação não pode ser desfeita.</p>
            
            <form method="POST">
                <div class="d-flex gap-2">
                    <button type="submit" name="confirmar_exclusao" class="btn btn-danger">
                        Sim, excluir <?= $idCifra ? 'esta cifra' : 'todas as cifras' ?>
                    </button>
                    <a href="<?= $idCifra ? "visualizar.php?idMusica={$idMusica}" : "list.php" ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
