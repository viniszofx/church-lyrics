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
    $sql = "DELETE FROM tbIgrejaSacerdote WHERE idIgrejaSacerdote = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Vínculo excluído com sucesso!</div>';
            echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 2000);</script>';
        } else {
            echo '<div class="alert alert-danger">Erro ao excluir vínculo: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
} else {
    // Buscar informações do vínculo para exibir na confirmação
    $sql = "SELECT v.idIgrejaSacerdote, i.NomeIgreja, s.NomeSacerdote
            FROM tbigrejasacerdote v
            JOIN tbigreja i ON v.idIgreja = i.idIgreja
            JOIN tbsacerdotes s ON v.idSacerdote = s.idSacerdote
            WHERE v.idIgrejaSacerdote = ?";
            
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
        echo '<div class="alert alert-danger">Vínculo não encontrado.</div>';
        echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
        $stmt->close();
        include_once('../../includes/footer.php');
        exit;
    }
    
    $vinculo = $result->fetch_assoc();
    $stmt->close();
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Excluir Vínculo</h2>
        <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Confirmar Exclusão</h5>
        </div>
        <div class="card-body">
            <p>Tem certeza que deseja excluir o vínculo entre:</p>
            
            <div class="alert alert-warning">
                <p><strong>Igreja:</strong> <?= htmlspecialchars($vinculo['NomeIgreja']) ?></p>
                <p><strong>Sacerdote:</strong> <?= htmlspecialchars($vinculo['NomeSacerdote']) ?></p>
            </div>
            
            <p class="text-danger">Esta ação não pode ser desfeita.</p>
            
            <form method="post">
                <div class="d-flex gap-2">
                    <button type="submit" name="confirmar_exclusao" class="btn btn-danger">
                        Sim, excluir vínculo
                    </button>
                    <a href="list.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
