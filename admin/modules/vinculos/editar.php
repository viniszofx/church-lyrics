<?php
include_once('../../includes/header.php');

$idVinculo = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensagem = '';
$vinculo = null;

// Se não tiver ID, redireciona para a lista
if ($idVinculo <= 0) {
    echo '<div class="alert alert-danger">ID de vínculo inválido.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    include_once('../../includes/footer.php');
    exit;
}

// Buscar dados do vínculo
$sql = "SELECT * FROM tbigrejasacerdote WHERE idIgrejaSacerdote = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    include_once('../../includes/footer.php');
    exit;
}

$stmt->bind_param("i", $idVinculo);
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

// Listar todas as igrejas para o dropdown
$sql_igrejas = "SELECT idIgreja, NomeIgreja FROM tbigreja ORDER BY NomeIgreja";
$result_igrejas = $conn->query($sql_igrejas);

// Listar todos os sacerdotes para o dropdown
$sql_sacerdotes = "SELECT idSacerdote, NomeSacerdote FROM tbsacerdotes ORDER BY NomeSacerdote";
$result_sacerdotes = $conn->query($sql_sacerdotes);

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idIgreja = isset($_POST['idIgreja']) ? intval($_POST['idIgreja']) : 0;
    $idSacerdote = isset($_POST['idSacerdote']) ? intval($_POST['idSacerdote']) : 0;
    $dataInicio = isset($_POST['dataInicio']) ? $_POST['dataInicio'] : null;
    $dataFim = isset($_POST['dataFim']) && !empty($_POST['dataFim']) ? $_POST['dataFim'] : null;
    $status = isset($_POST['status']) ? 1 : 0;
    
    // Validações
    if ($idIgreja <= 0) {
        $mensagem = '<div class="alert alert-danger">Selecione uma igreja válida.</div>';
    } elseif ($idSacerdote <= 0) {
        $mensagem = '<div class="alert alert-danger">Selecione um sacerdote válido.</div>';
    } elseif (empty($dataInicio)) {
        $mensagem = '<div class="alert alert-danger">A data de início é obrigatória.</div>';
    } else {
        // Verifica se já existe um vínculo entre esta igreja e este sacerdote (exceto o atual)
        $sql_check = "SELECT idIgrejaSacerdote FROM tbigrejasacerdote 
                     WHERE idIgreja = ? AND idSacerdote = ? AND idIgrejaSacerdote != ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("iii", $idIgreja, $idSacerdote, $idVinculo);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $mensagem = '<div class="alert alert-danger">Já existe um vínculo entre esta igreja e este sacerdote.</div>';
            $stmt_check->close();
        } else {
            $stmt_check->close();
            
            // Atualiza o vínculo
            $sql_update = "UPDATE tbigrejasacerdote SET 
                          idIgreja = ?, 
                          idSacerdote = ?, 
                          DataInicio = ?, 
                          DataFim = ?, 
                          Status = ? 
                          WHERE idIgrejaSacerdote = ?";
                          
            $stmt_update = $conn->prepare($sql_update);
            
            if (!$stmt_update) {
                $mensagem = '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
            } else {
                $stmt_update->bind_param("iissii", $idIgreja, $idSacerdote, $dataInicio, $dataFim, $status, $idVinculo);
                
                if ($stmt_update->execute()) {
                    $mensagem = '<div class="alert alert-success">Vínculo atualizado com sucesso!</div>';
                    
                    // Recarregar dados do vínculo após atualização
                    $sql = "SELECT * FROM tbigrejasacerdote WHERE idIgrejaSacerdote = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $idVinculo);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $vinculo = $result->fetch_assoc();
                    $stmt->close();
                } else {
                    $mensagem = '<div class="alert alert-danger">Erro ao atualizar vínculo: ' . $stmt_update->error . '</div>';
                }
                
                $stmt_update->close();
            }
        }
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Vínculo Igreja-Sacerdote</h2>
        <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
    </div>
    
    <?php echo $mensagem; ?>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="idIgreja" class="form-label">Igreja</label>
                    <select class="form-select" id="idIgreja" name="idIgreja" required>
                        <option value="">Selecione uma igreja</option>
                        <?php
                        if ($result_igrejas) {
                            while ($igreja = $result_igrejas->fetch_assoc()) {
                                $selected = ($igreja['idIgreja'] == $vinculo['idIgreja']) ? 'selected' : '';
                                echo '<option value="' . $igreja['idIgreja'] . '" ' . $selected . '>' . 
                                     htmlspecialchars($igreja['NomeIgreja']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="idSacerdote" class="form-label">Sacerdote</label>
                    <select class="form-select" id="idSacerdote" name="idSacerdote" required>
                        <option value="">Selecione um sacerdote</option>
                        <?php
                        if ($result_sacerdotes) {
                            while ($sacerdote = $result_sacerdotes->fetch_assoc()) {
                                $selected = ($sacerdote['idSacerdote'] == $vinculo['idSacerdote']) ? 'selected' : '';
                                echo '<option value="' . $sacerdote['idSacerdote'] . '" ' . $selected . '>' . 
                                     htmlspecialchars($sacerdote['NomeSacerdote']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="dataInicio" class="form-label">Data de Início</label>
                    <input type="date" class="form-control" id="dataInicio" name="dataInicio" 
                           value="<?= htmlspecialchars($vinculo['DataInicio']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="dataFim" class="form-label">Data de Fim</label>
                    <input type="date" class="form-control" id="dataFim" name="dataFim" 
                           value="<?= htmlspecialchars($vinculo['DataFim'] ?? '') ?>">
                    <div class="form-text text-muted">Deixe em branco se o vínculo ainda está ativo</div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="status" name="status" 
                           <?= ($vinculo['Status'] == 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="status">Vínculo ativo</label>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="list.php" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
