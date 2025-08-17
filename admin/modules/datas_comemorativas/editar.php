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
    $desc = mysqli_real_escape_string($conn, $_POST['DescComemoracao']);
    $quando = mysqli_real_escape_string($conn, $_POST['QuandoAcontece']);
    $mesdia = mysqli_real_escape_string($conn, $_POST['MesDia']);
    
    $sql = "UPDATE tbdatacomemorativa SET DescComemoracao=?, QuandoAcontece=?, MesDia=? WHERE idDataComemorativa=?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param("sssi", $desc, $quando, $mesdia, $id);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Data comemorativa atualizada com sucesso!</div>';
            echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 2000);</script>';
        } else {
            echo '<div class="alert alert-danger">Erro ao atualizar: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Buscar dados da data comemorativa
$sql = "SELECT * FROM tbdatacomemorativa WHERE idDataComemorativa = ?";
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
        <h2>Editar Data Comemorativa</h2>
        <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="DescComemoracao" class="form-label">Nome Comemoração</label>
                    <input type="text" name="DescComemoracao" id="DescComemoracao" class="form-control" 
                           value="<?= htmlspecialchars($row['DescComemoracao']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="QuandoAcontece" class="form-label">Quando Acontece</label>
                    <input type="text" name="QuandoAcontece" id="QuandoAcontece" class="form-control" 
                           value="<?= htmlspecialchars($row['QuandoAcontece']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="MesDia" class="form-label">Mês/Dia</label>
                    <input type="text" name="MesDia" id="MesDia" class="form-control" 
                           value="<?= htmlspecialchars($row['MesDia']) ?>" required>
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
