<?php
include_once('../../includes/header.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

$igreja = '';
$sacerdote = '';
$dataInicio = '';
$dataFim = '';
$status = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idIgrejaSacerdote = isset($_POST['idIgrejaSacerdote']) ? intval($_POST['idIgrejaSacerdote']) : null;
    $idIgreja = intval($_POST['idIgreja']);
    $idSacerdote = intval($_POST['idSacerdote']);
    $dataInicio = mysqli_real_escape_string($conn, $_POST['DataInicio']);
    $dataFim = !empty($_POST['DataFim']) ? mysqli_real_escape_string($conn, $_POST['DataFim']) : null;
    $status = intval($_POST['Status']);

    if ($idIgrejaSacerdote) {
        $sql = "UPDATE tbIgrejaSacerdote SET idIgreja = ?, idSacerdote = ?, DataInicio = ?, DataFim = ?, Status = ? 
                WHERE idIgrejaSacerdote = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
        } else {
            $stmt->bind_param("iissii", $idIgreja, $idSacerdote, $dataInicio, $dataFim, $status, $idIgrejaSacerdote);
            
            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Vínculo atualizado com sucesso!</div>';
                echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 2000);</script>';
            } else {
                echo '<div class="alert alert-danger">Erro ao atualizar vínculo: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    } else {
        $sql = "INSERT INTO tbIgrejaSacerdote (idIgreja, idSacerdote, DataInicio, DataFim, Status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
        } else {
            $stmt->bind_param("iissi", $idIgreja, $idSacerdote, $dataInicio, $dataFim, $status);
            
            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Novo vínculo criado com sucesso!</div>';
                echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 2000);</script>';
            } else {
                echo '<div class="alert alert-danger">Erro ao inserir vínculo: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }
}

if ($id) {
    $sql = "SELECT * FROM tbIgrejaSacerdote WHERE idIgrejaSacerdote = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
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
        
        $res = $result->fetch_assoc();
        $igreja = $res['idIgreja'];
        $sacerdote = $res['idSacerdote'];
        $dataInicio = $res['DataInicio'];
        $dataFim = $res['DataFim'];
        $status = $res['Status'];
        $stmt->close();
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $id ? 'Editar Vínculo' : 'Novo Vínculo' ?> Igreja x Sacerdote</h2>
        <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <?php if ($id): ?>
                <input type="hidden" name="idIgrejaSacerdote" value="<?= $id ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="idIgreja" class="form-label">Igreja</label>
                    <select name="idIgreja" id="idIgreja" class="form-select" required>
                        <option value="">Selecione uma igreja</option>
                        <?php
                        $sql = "SELECT idIgreja, NomeIgreja FROM tbIgreja ORDER BY NomeIgreja";
                        $result = $conn->query($sql);
                        
                        if (!$result) {
                            echo '<option value="">Erro ao carregar igrejas: ' . $conn->error . '</option>';
                        } else {
                            while ($row = $result->fetch_assoc()) {
                                $selected = ($igreja == $row['idIgreja']) ? 'selected' : '';
                                echo '<option value="' . $row['idIgreja'] . '" ' . $selected . '>' . htmlspecialchars($row['NomeIgreja']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="idSacerdote" class="form-label">Sacerdote</label>
                    <select name="idSacerdote" id="idSacerdote" class="form-select" required>
                        <option value="">Selecione um sacerdote</option>
                        <?php
                        $sql = "SELECT idSacerdote, NomeSacerdote FROM tbSacerdotes ORDER BY NomeSacerdote";
                        $result = $conn->query($sql);
                        
                        if (!$result) {
                            echo '<option value="">Erro ao carregar sacerdotes: ' . $conn->error . '</option>';
                        } else {
                            while ($row = $result->fetch_assoc()) {
                                $selected = ($sacerdote == $row['idSacerdote']) ? 'selected' : '';
                                echo '<option value="' . $row['idSacerdote'] . '" ' . $selected . '>' . htmlspecialchars($row['NomeSacerdote']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="DataInicio" class="form-label">Data Início</label>
                    <input type="date" name="DataInicio" id="DataInicio" class="form-control" value="<?= $dataInicio ?>" required>
                </div>

                <div class="mb-3">
                    <label for="DataFim" class="form-label">Data Fim</label>
                    <input type="date" name="DataFim" id="DataFim" class="form-control" value="<?= $dataFim ?>">
                    <small class="text-muted">Deixe em branco se o vínculo estiver ativo sem data para término</small>
                </div>

                <div class="mb-3">
                    <label for="Status" class="form-label">Status</label>
                    <select name="Status" id="Status" class="form-select" required>
                        <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Ativo</option>
                        <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="list.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
