<?php
include_once('../../includes/header.php');

// Verificar se o ID foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="alert alert-danger">ID não fornecido.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    include_once('../../includes/footer.php');
    exit;
}

$id = intval($_GET['id']);

// Processar o formulário quando enviado
if (isset($_POST['salvar'])) {
    $desc = mysqli_real_escape_string($conn, $_POST['DescMomento']);
    $ordem = mysqli_real_escape_string($conn, $_POST['OrdemDeExecucao']);
    
    $sql = "UPDATE tbmomentosmissa SET DescMomento=?, OrdemDeExecucao=? WHERE idMomento=?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param("ssi", $desc, $ordem, $id);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Momento atualizado com sucesso!</div>';
            echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 2000);</script>';
        } else {
            echo '<div class="alert alert-danger">Erro ao atualizar: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Buscar dados do momento
$sql = "SELECT * FROM tbmomentosmissa WHERE idMomento = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    include_once('../../includes/footer.php');
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<div class="alert alert-danger">Registro não encontrado.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    $stmt->close();
    include_once('../../includes/footer.php');
    exit;
}

$row = $result->fetch_assoc();
$stmt->close();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Momento da Missa</h2>
        <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="DescMomento" class="form-label">Descrição</label>
                    <input type="text" name="DescMomento" id="DescMomento" class="form-control" 
                           value="<?= htmlspecialchars($row['DescMomento']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="OrdemDeExecucao" class="form-label">Ordem de Execução</label>
                    <input type="text" name="OrdemDeExecucao" id="OrdemDeExecucao" class="form-control" 
                           value="<?= htmlspecialchars($row['OrdemDeExecucao']) ?>" required>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
                    <a href="list.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
