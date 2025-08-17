<?php
include_once('../../includes/header.php');

// Verificar se o ID foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="alert alert-danger">ID da missa não fornecido.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    include_once('../../includes/footer.php');
    exit;
}

$idMissa = intval($_GET['id']);

// Buscar informações da missa para confirmar a exclusão
$sql = "SELECT m.TituloMissa, m.DataMissa, i.NomeIgreja 
        FROM tbmissa m 
        LEFT JOIN tbigreja i ON m.idIgreja = i.idIgreja 
        WHERE m.idMissa = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    include_once('../../includes/footer.php');
    exit;
}

$stmt->bind_param('i', $idMissa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<div class="alert alert-danger">Missa não encontrada.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    $stmt->close();
    include_once('../../includes/footer.php');
    exit;
}

$missa = $result->fetch_assoc();
$stmt->close();

// Processar a exclusão
if (isset($_POST['confirmar_exclusao'])) {
    // Iniciar transação
    $conn->begin_transaction();
    
    try {
        // Excluir registros relacionados em tbmissamomentomusica
        $sql = "DELETE FROM tbmissamomentomusica WHERE idMissa = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }
        
        $stmt->bind_param('i', $idMissa);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao excluir relacionamentos: " . $stmt->error);
        }
        
        $stmt->close();
        
        // Excluir a missa
        $sql = "DELETE FROM tbmissa WHERE idMissa = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }
        
        $stmt->bind_param('i', $idMissa);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao excluir missa: " . $stmt->error);
        }
        
        $stmt->close();
        
        // Confirmar transação
        $conn->commit();
        
        echo '<div class="alert alert-success">Missa excluída com sucesso!</div>';
        echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 2000);</script>';
        
    } catch (Exception $e) {
        // Reverter transação em caso de erro
        $conn->rollback();
        echo '<div class="alert alert-danger">Erro: ' . $e->getMessage() . '</div>';
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Excluir Missa</h2>
        <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Confirmar Exclusão</h5>
        </div>
        <div class="card-body">
            <p>Tem certeza que deseja excluir a seguinte missa?</p>
            
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Título:</strong> <?= htmlspecialchars($missa['TituloMissa']) ?></li>
                <li class="list-group-item"><strong>Data:</strong> <?= date('d/m/Y', strtotime($missa['DataMissa'])) ?></li>
                <li class="list-group-item"><strong>Igreja:</strong> <?= htmlspecialchars($missa['NomeIgreja'] ?? 'Não informada') ?></li>
            </ul>
            
            <p class="text-danger">Esta ação excluirá permanentemente todos os dados relacionados a esta missa, incluindo todos os vínculos com músicas e momentos.</p>
            <p class="text-danger"><strong>Atenção:</strong> Esta ação não pode ser desfeita!</p>
            
            <form method="POST">
                <div class="d-flex gap-2">
                    <button type="submit" name="confirmar_exclusao" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Sim, excluir esta missa
                    </button>
                    <a href="list.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
