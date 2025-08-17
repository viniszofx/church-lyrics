<?php
include_once('../../includes/header.php');
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Vínculos Igreja x Sacerdote</h2>
        <div>
            <a href="form.php" class="btn btn-success me-2">Novo Vínculo</a>
            <a href="../../index.php" class="btn btn-secondary">Voltar ao Menu</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Igreja</th>
                            <th>Sacerdote</th>
                            <th>Data Início</th>
                            <th>Data Fim</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT v.idIgrejaSacerdote, i.NomeIgreja, s.NomeSacerdote, 
                                   v.DataInicio, v.DataFim, v.Status as ativo
                            FROM tbigrejasacerdote v
                            JOIN tbigreja i ON v.idIgreja = i.idIgreja
                            JOIN tbsacerdotes s ON v.idSacerdote = s.idSacerdote
                            ORDER BY i.NomeIgreja, s.NomeSacerdote";
                    
                    $result = $conn->query($sql);

                    if (!$result) {
                        echo '<tr><td colspan="6" class="text-center text-danger">Erro na consulta SQL: ' . $conn->error . '</td></tr>';
                    } elseif ($result->num_rows == 0) {
                        echo '<tr><td colspan="6" class="text-center">Nenhum vínculo encontrado</td></tr>';
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            $dataInicio = !empty($row['DataInicio']) ? date('d/m/Y', strtotime($row['DataInicio'])) : '';
                            $dataFim = !empty($row['DataFim']) ? date('d/m/Y', strtotime($row['DataFim'])) : '-';
                            $status = $row['ativo'] ? 
                                '<span class="badge bg-success">Ativo</span>' : 
                                '<span class="badge bg-danger">Inativo</span>';
                            
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['NomeIgreja']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['NomeSacerdote']) . '</td>';
                            echo '<td>' . $dataInicio . '</td>';
                            echo '<td>' . $dataFim . '</td>';
                            echo '<td>' . $status . '</td>';
                            echo '<td>';
                            echo '<a href="editar.php?id=' . $row['idIgrejaSacerdote'] . '" class="btn btn-sm btn-warning me-2">';
                            echo '<i class="bi bi-pencil"></i> Editar</a>';
                            echo '<a href="#" onclick="if(confirm(\'Tem certeza que deseja excluir este vínculo?\')) { window.location.href=\'excluir.php?id=' . $row['idIgrejaSacerdote'] . '\'; }" ';
                            echo 'class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Excluir</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
