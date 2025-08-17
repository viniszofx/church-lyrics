<?php
include_once('../../includes/header.php');

// Filtros
$nomeMusica = isset($_GET['nome']) ? $_GET['nome'] : '';
$idTpLiturgico = isset($_GET['tpliturgico']) ? $_GET['tpliturgico'] : '';
$idMomento = isset($_GET['momento']) ? $_GET['momento'] : '';

// Query para dropdowns
$tpliturgico_query = "SELECT idTpLiturgico, DescTempo FROM tbtpliturgico ORDER BY DescTempo";
$tpliturgico_result = $conn->query($tpliturgico_query);

if (!$tpliturgico_result) {
    echo '<div class="alert alert-danger">Erro ao buscar tempos litúrgicos: ' . $conn->error . '</div>';
}

$momentos_query = "SELECT idMomento, DescMomento FROM tbmomentosmissa ORDER BY DescMomento";
$momentos_result = $conn->query($momentos_query);

if (!$momentos_result) {
    echo '<div class="alert alert-danger">Erro ao buscar momentos: ' . $conn->error . '</div>';
}

// Query principal
$sql = "SELECT  
            m.idMusica, m.NomeMusica, 
            ANY_VALUE(mm.DescMomento) as DescMomento, 
            ANY_VALUE(mm.idMomento) as idMomento, 
            ANY_VALUE(tl.DescTempo) as DescTempo, 
            ANY_VALUE(tl.idTpLiturgico) as idTpLiturgico, 
            ANY_VALUE(tl.Sigla) as Sigla,
            COUNT(c.idCifra) as QtdeCifras
        FROM tbmusica m
        LEFT JOIN tbmusicamomentomissa mmm ON m.idMusica = mmm.idMusica
        LEFT JOIN tbmomentosmissa mm ON mmm.idMomento = mm.idMomento
        LEFT JOIN tbcifras c ON m.idMusica = c.idMusica
        LEFT JOIN tbtempomusica tm ON m.idMusica = tm.idMusica
        LEFT JOIN tbtpliturgico tl ON tm.idTpLiturgico = tl.idTpLiturgico
        WHERE 1 = 1";

// Aplicar filtros
if (!empty($nomeMusica)) {
    $nomeMusica = mysqli_real_escape_string($conn, $nomeMusica);
    $sql .= " AND m.NomeMusica LIKE '%$nomeMusica%'";
}

if (!empty($idTpLiturgico)) {
    $idTpLiturgico = intval($idTpLiturgico);
    $sql .= " AND tl.idTpLiturgico = $idTpLiturgico";
}

if (!empty($idMomento)) {
    $idMomento = intval($idMomento);
    $sql .= " AND mm.idMomento = $idMomento";
}

$sql .= " GROUP BY m.idMusica ORDER BY ANY_VALUE(COALESCE(mm.OrdemDeExecucao, '9999')), m.NomeMusica ASC";

// Execute query with error handling
$result = $conn->query($sql);
if (!$result) {
    echo '<div class="alert alert-danger">Erro na consulta SQL: ' . $conn->error . '<br>Query: ' . $sql . '</div>';
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Listagem de Cifras</h2>
        <a href="../../index.php" class="btn btn-secondary">Voltar ao menu principal</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Filtros</h5>
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="nome" class="form-label">Nome da Música</label>
                    <input type="text" name="nome" id="nome" class="form-control" 
                           placeholder="Buscar por Nome da Música" value="<?= htmlspecialchars($nomeMusica) ?>">
                </div>
                
                <div class="col-md-3">
                    <label for="tpliturgico" class="form-label">Tempo Litúrgico</label>
                    <select name="tpliturgico" id="tpliturgico" class="form-select">
                        <option value="">Todos</option>
                        <?php 
                        if ($tpliturgico_result) {
                            while ($row = $tpliturgico_result->fetch_assoc()): ?>
                                <option value="<?= $row['idTpLiturgico'] ?>" <?= ($row['idTpLiturgico'] == $idTpLiturgico) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['DescTempo']) ?>
                                </option>
                            <?php endwhile; 
                        }
                        ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="momento" class="form-label">Momento</label>
                    <select name="momento" id="momento" class="form-select">
                        <option value="">Todos</option>
                        <?php 
                        if ($momentos_result) {
                            while ($row = $momentos_result->fetch_assoc()): ?>
                                <option value="<?= $row['idMomento'] ?>" <?= ($row['idMomento'] == $idMomento) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['DescMomento']) ?>
                                </option>
                            <?php endwhile;
                        }
                        ?>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Resultado</h5>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Música</th>
                            <th>Qtde de Cifras</th>
                            <th>Momento</th>
                            <th>Tempo Litúrgico</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result && $result->num_rows > 0) {
                            while ($musica = $result->fetch_assoc()) { 
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($musica['NomeMusica']) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-success"><?= $musica['QtdeCifras'] ?></span>
                                </td>
                                <td><?= htmlspecialchars($musica['DescMomento'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($musica['DescTempo'] ?? 'N/A') ?>
                                    <?= !empty($musica['Sigla']) ? ' (' . htmlspecialchars($musica['Sigla']) . ')' : '' ?>
                                </td>
                                <td class="text-nowrap">
                                    <a href="form.php?idMusica=<?= $musica['idMusica'] ?>" 
                                       class="btn btn-success btn-sm">
                                        <i class="bi bi-plus-circle"></i> Incluir
                                    </a>
                                    <a href="visualizar.php?idMusica=<?= $musica['idMusica'] ?>" 
                                       class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> Visualizar
                                    </a>
                                    <a href="editar.php?idMusica=<?= $musica['idMusica'] ?>" 
                                       class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <a href="excluir.php?idMusica=<?= $musica['idMusica'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Deseja excluir todas as cifras desta música?');">
                                        <i class="bi bi-trash"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center">Nenhuma cifra encontrada.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
