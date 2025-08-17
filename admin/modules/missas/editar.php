<?php
include_once('../../includes/header.php');

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID da missa não informado.</div>";
    include_once('../../includes/footer.php');
    exit;
}

$idMissa = (int) $_GET['id'];

// Atualizar missa
if (isset($_POST['salvar'])) {
    $data = $_POST['DataMissa'];
    $ano = $_POST['AnoMissa'];
    $titulo = $_POST['TituloMissa'];
    $status = $_POST['Status'];
    $idIgreja = $_POST['idIgreja'];
    $idDataComemorativa = empty($_POST['idDataComemorativa']) ? null : $_POST['idDataComemorativa'];
    $musicasSelecionadas = $_POST['musicas'] ?? [];

    $sql = "UPDATE tbmissa SET
        DataMissa = ?,
        AnoMissa = ?,
        TituloMissa = ?,
        Status = ?,
        idIgreja = ?,
        idDataComemorativa = ?
        WHERE idMissa = ?";
        
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da atualização: ' . $conn->error . '</div>';
        include_once('../../includes/footer.php');
        exit;
    }
    
    $stmt->bind_param("sssiiis", $data, $ano, $titulo, $status, $idIgreja, $idDataComemorativa, $idMissa);
    if (!$stmt->execute()) {
        echo '<div class="alert alert-danger">Erro ao atualizar missa: ' . $stmt->error . '</div>';
        $stmt->close();
        include_once('../../includes/footer.php');
        exit;
    }
    $stmt->close();

    // Limpar vínculos antigos - usando prepared statement
    $sqlDelete = "DELETE FROM tbmissamusicas WHERE idMissa = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    if (!$stmtDelete) {
        echo '<div class="alert alert-danger">Erro ao preparar exclusão de músicas: ' . $conn->error . '</div>';
        include_once('../../includes/footer.php');
        exit;
    }
    
    $stmtDelete->bind_param("i", $idMissa);
    if (!$stmtDelete->execute()) {
        echo '<div class="alert alert-danger">Erro ao excluir músicas anteriores: ' . $stmtDelete->error . '</div>';
        $stmtDelete->close();
        include_once('../../includes/footer.php');
        exit;
    }
    $stmtDelete->close();

    // Inserir novos vínculos
    if (!empty($musicasSelecionadas)) {
        $sqlInsert = "INSERT INTO tbmissamusicas (idMusica, idMissa, idMusicaMomentoMissa) VALUES (?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        if (!$stmtInsert) {
            echo '<div class="alert alert-danger">Erro ao preparar inserção de músicas: ' . $conn->error . '</div>';
            include_once('../../includes/footer.php');
            exit;
        }
        
        foreach ($musicasSelecionadas as $mm) {
            list($idMusica, $idMomento) = explode("_", $mm);
            $stmtInsert->bind_param("iii", $idMusica, $idMissa, $idMomento);
            if (!$stmtInsert->execute()) {
                echo '<div class="alert alert-danger">Erro ao inserir músicas: ' . $stmtInsert->error . '</div>';
                $stmtInsert->close();
                include_once('../../includes/footer.php');
                exit;
            }
        }
        $stmtInsert->close();
    }

    echo '<div class="alert alert-success">Missa atualizada com sucesso!</div>';
    echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 1500);</script>';
}

// Dados da missa
$sqlMissa = "SELECT * FROM tbmissa WHERE idMissa = ?";
$stmtMissa = $conn->prepare($sqlMissa);

if (!$stmtMissa) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    include_once('../../includes/footer.php');
    exit;
}

$stmtMissa->bind_param("i", $idMissa);
$stmtMissa->execute();
$resultMissa = $stmtMissa->get_result();

if ($resultMissa->num_rows === 0) {
    echo '<div class="alert alert-danger">Missa não encontrada.</div>';
    $stmtMissa->close();
    include_once('../../includes/footer.php');
    exit;
}

$missa = $resultMissa->fetch_assoc();
$stmtMissa->close();

// Listas auxiliares
$sqlIgrejas = "SELECT idIgreja, NomeIgreja FROM tbigreja ORDER BY NomeIgreja";
$resultIgrejas = $conn->query($sqlIgrejas);
if (!$resultIgrejas) {
    echo '<div class="alert alert-danger">Erro ao buscar igrejas: ' . $conn->error . '</div>';
    $igrejas = [];
} else {
    $igrejas = $resultIgrejas->fetch_all(MYSQLI_ASSOC);
}

$sqlDatasComemorativas = "SELECT idDataComemorativa, DescComemoracao FROM tbdatacomemorativa ORDER BY DescComemoracao";
$resultDatasComemorativas = $conn->query($sqlDatasComemorativas);
if (!$resultDatasComemorativas) {
    echo '<div class="alert alert-danger">Erro ao buscar datas comemorativas: ' . $conn->error . '</div>';
    $datasComemorativas = [];
} else {
    $datasComemorativas = $resultDatasComemorativas->fetch_all(MYSQLI_ASSOC);
}

$sqlMomentos = "SELECT idMomento, DescMomento FROM tbmomentosmissa ORDER BY OrdemDeExecucao";
$resultMomentos = $conn->query($sqlMomentos);
if (!$resultMomentos) {
    echo '<div class="alert alert-danger">Erro ao buscar momentos da missa: ' . $conn->error . '</div>';
    $momentos = [];
} else {
    $momentos = $resultMomentos->fetch_all(MYSQLI_ASSOC);
}

// Músicas por momento
$musicasPorMomento = [];
foreach ($momentos as $momento) {
    $idMomento = $momento['idMomento'];
    $sqlMusicas = "
        SELECT m.idMusica, m.NomeMusica
        FROM tbmusicamomentomissa mm
        JOIN tbmusica m ON m.idMusica = mm.idMusica
        WHERE mm.idMomento = ?
        ORDER BY m.NomeMusica";
        
    $stmtMusicas = $conn->prepare($sqlMusicas);
    if (!$stmtMusicas) {
        echo '<div class="alert alert-danger">Erro ao preparar consulta de músicas: ' . $conn->error . '</div>';
        continue;
    }
    
    $stmtMusicas->bind_param("i", $idMomento);
    $stmtMusicas->execute();
    $resultMusicas = $stmtMusicas->get_result();
    
    if (!$resultMusicas) {
        echo '<div class="alert alert-danger">Erro ao buscar músicas para o momento: ' . $stmtMusicas->error . '</div>';
        $stmtMusicas->close();
        continue;
    }
    
    $musicasPorMomento[$idMomento] = $resultMusicas->fetch_all(MYSQLI_ASSOC);
    $stmtMusicas->close();
}

// Músicas já associadas
$musicasMarcadas = [];
$sqlMusicasMarcadas = "SELECT idMusica, idMusicaMomentoMissa FROM tbmissamusicas WHERE idMissa = ?";
$stmtMusicasMarcadas = $conn->prepare($sqlMusicasMarcadas);

if (!$stmtMusicasMarcadas) {
    echo '<div class="alert alert-danger">Erro ao preparar consulta de músicas marcadas: ' . $conn->error . '</div>';
} else {
    $stmtMusicasMarcadas->bind_param("i", $idMissa);
    $stmtMusicasMarcadas->execute();
    $resultMusicasMarcadas = $stmtMusicasMarcadas->get_result();
    
    if (!$resultMusicasMarcadas) {
        echo '<div class="alert alert-danger">Erro ao buscar músicas marcadas: ' . $stmtMusicasMarcadas->error . '</div>';
    } else {
        while ($row = $resultMusicasMarcadas->fetch_assoc()) {
            $musicasMarcadas[] = $row['idMusica'] . '_' . $row['idMusicaMomentoMissa'];
        }
    }
    $stmtMusicasMarcadas->close();
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>✏️ Editar Missa</h2>
        <a href="list.php" class="btn btn-secondary">← Voltar à lista</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Data da Missa</label>
                        <input type="date" name="DataMissa" class="form-control" value="<?= $missa['DataMissa'] ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ano</label>
                        <input type="text" name="AnoMissa" class="form-control" value="<?= $missa['AnoMissa'] ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Título da Missa</label>
                        <input type="text" name="TituloMissa" class="form-control" value="<?= htmlspecialchars($missa['TituloMissa']) ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="Status" class="form-select">
                            <option value="1" <?= $missa['Status'] == 1 ? 'selected' : '' ?>>Ativa</option>
                            <option value="0" <?= $missa['Status'] == 0 ? 'selected' : '' ?>>Inativa</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Igreja</label>
                        <select name="idIgreja" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($igrejas as $i): ?>
                                <option value="<?= $i['idIgreja'] ?>" <?= $missa['idIgreja'] == $i['idIgreja'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($i['NomeIgreja']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Data Comemorativa</label>
                        <select name="idDataComemorativa" class="form-select">
                            <option value="">Nenhuma</option>
                            <?php foreach ($datasComemorativas as $d): ?>
                                <option value="<?= $d['idDataComemorativa'] ?>" <?= $missa['idDataComemorativa'] == $d['idDataComemorativa'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d['DescComemoracao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <h5 class="mt-4 mb-2">🎵 Músicas da Missa</h5>

                <?php if (empty($momentos)): ?>
                    <div class="alert alert-warning">Nenhum momento da missa encontrado.</div>
                <?php else: ?>
                    <?php foreach ($momentos as $mom): ?>
                        <?php if (!empty($musicasPorMomento[$mom['idMomento']])): ?>
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <strong><?= htmlspecialchars($mom['DescMomento']) ?></strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($musicasPorMomento[$mom['idMomento']] as $mus): 
                                            $value = $mus['idMusica'] . '_' . $mom['idMomento'];
                                        ?>
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="musicas[]"
                                                        value="<?= $value ?>"
                                                        id="check<?= $value ?>"
                                                        <?= in_array($value, $musicasMarcadas) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="check<?= $value ?>">
                                                        <?= htmlspecialchars($mus['NomeMusica']) ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" name="salvar" class="btn btn-success">
                        <i class="bi bi-save"></i> Salvar Alterações
                    </button>
                    <a href="list.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
