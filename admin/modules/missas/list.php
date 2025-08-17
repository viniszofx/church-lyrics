<?php
include_once('../../includes/header.php');

// Receber filtros via GET
$ano = isset($_GET['ano']) ? mysqli_real_escape_string($conn, $_GET['ano']) : '';
$data = isset($_GET['data']) ? mysqli_real_escape_string($conn, $_GET['data']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$titulo = isset($_GET['titulo']) ? mysqli_real_escape_string($conn, $_GET['titulo']) : '';
$comemoracao = isset($_GET['comemoracao']) ? mysqli_real_escape_string($conn, $_GET['comemoracao']) : '';

// Consulta com filtros
$sql = "
SELECT m.idMissa, m.DataMissa, m.AnoMissa, m.TituloMissa, m.Status,
       i.NomeIgreja, dc.DescComemoracao
FROM tbmissa m
LEFT JOIN tbigreja i ON m.idIgreja = i.idIgreja
LEFT JOIN tbdatacomemorativa dc ON m.idDataComemorativa = dc.idDataComemorativa
WHERE 1=1
";

$params = [];
$types = '';

if (!empty($ano)) {
    $sql .= " AND m.AnoMissa = ?";
    $params[] = $ano;
    $types .= 's';
}

if (!empty($data)) {
    $sql .= " AND m.DataMissa = ?";
    $params[] = $data;
    $types .= 's';
}

if (!empty($titulo)) {
    $sql .= " AND m.TituloMissa LIKE ?";
    $params[] = "%$titulo%";
    $types .= 's';
}

if ($status !== '') {
    $sql .= " AND m.Status = ?";
    $params[] = $status;
    $types .= 'i';
}

if (!empty($comemoracao)) {
    $sql .= " AND dc.DescComemoracao LIKE ?";
    $params[] = "%$comemoracao%";
    $types .= 's';
}

$sql .= " ORDER BY m.DataMissa DESC";

// Preparar e executar a consulta
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    $missas = [];
} else {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        echo '<div class="alert alert-danger">Erro na execução da consulta: ' . $stmt->error . '</div>';
        $missas = [];
    } else {
        $missas = $result->fetch_all(MYSQLI_ASSOC);
    }
    
    $stmt->close();
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Missas Cadastradas</h2>
        <a href="../../index.php" class="btn btn-secondary">Voltar ao menu principal</a>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <form method="get" class="row g-2">
                <div class="col-md-2">
                    <label for="ano" class="form-label">Ano</label>
                    <input type="text" id="ano" name="ano" class="form-control" 
                           placeholder="Ano" value="<?= htmlspecialchars($ano) ?>">
                </div>
                <div class="col-md-2">
                    <label for="data" class="form-label">Data</label>
                    <input type="date" id="data" name="data" class="form-control" 
                           value="<?= htmlspecialchars($data) ?>">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Ativa</option>
                        <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Inativa</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="comemoracao" class="form-label">Comemoração</label>
                    <input type="text" id="comemoracao" name="comemoracao" class="form-control" 
                           placeholder="Comemoração" value="<?= htmlspecialchars($comemoracao) ?>">
                </div>
                <div class="col-md-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" 
                           placeholder="Título da Missa" value="<?= htmlspecialchars($titulo) ?>">
                </div>
                <div class="col-12 mt-3 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Botões principais -->
    <div class="mb-4 text-end">
        <a href="form.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Cadastrar nova missa
        </a>
    </div>

    <!-- Tabela de Missas -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Resultado</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($missas)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Data</th>
                                <th>Ano</th>
                                <th>Status</th>
                                <th>Igreja</th>
                                <th>Comemoração</th>
                                <th>Título</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($missas as $m): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($m['DataMissa'])) ?></td>
                                    <td><?= htmlspecialchars($m['AnoMissa']) ?></td>
                                    <td>
                                        <?= $m['Status'] ? 
                                            '<span class="badge bg-success">Ativa</span>' : 
                                            '<span class="badge bg-secondary">Inativa</span>' ?>
                                    </td>
                                    <td><?= htmlspecialchars($m['NomeIgreja'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($m['DescComemoracao'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($m['TituloMissa']) ?></td>
                                    <td class="text-nowrap">
                                        <a href="editar.php?id=<?= $m['idMissa'] ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <a href="excluir.php?id=<?= $m['idMissa'] ?>" class="btn btn-sm btn-danger me-1"
                                           onclick="return confirm('Deseja realmente excluir esta missa?')">
                                            <i class="bi bi-trash"></i> Excluir
                                        </a>
                                        <a href="detalhes.php?id=<?= $m['idMissa'] ?>" class="btn btn-sm btn-info me-1">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Exportar
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="export_excel.php?id=<?= $m['idMissa'] ?>" class="dropdown-item">
                                                        <i class="bi bi-file-excel"></i> Excel
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="export_pdf.php?id=<?= $m['idMissa'] ?>" class="dropdown-item">
                                                        <i class="bi bi-file-pdf"></i> PDF
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    Nenhuma missa encontrada com os filtros informados.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
