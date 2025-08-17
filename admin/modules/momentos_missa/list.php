<?php 
include_once('../../includes/header.php');

// Inserir novo momento
if (isset($_POST['inserir'])) {
    $desc = mysqli_real_escape_string($conn, $_POST['DescMomento']);
    $ordem = mysqli_real_escape_string($conn, $_POST['OrdemDeExecucao']);
    
    $sql = "INSERT INTO tbmomentosmissa (DescMomento, OrdemDeExecucao) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param("ss", $desc, $ordem);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Momento cadastrado com sucesso!</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao cadastrar: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Excluir momento
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    
    $sql = "DELETE FROM tbmomentosmissa WHERE idMomento = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo '<div class="alert alert-danger">Momento excluído com sucesso!</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao excluir: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Momentos da Missa</h2>
        <a href="../../index.php" class="btn btn-secondary">Voltar ao menu principal</a>
    </div>

    <form method="post" class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Novo Momento da Missa</h5>
            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <input type="text" name="DescMomento" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ordem de Execução</label>
                <input type="text" name="OrdemDeExecucao" class="form-control" required>
            </div>
            <button type="submit" name="inserir" class="btn btn-primary">Salvar</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Lista de Momentos</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Ordem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT * FROM tbmomentosmissa ORDER BY OrdemDeExecucao ASC";
                    $result = $conn->query($sql);
                    
                    if (!$result) {
                        echo '<tr><td colspan="4" class="text-center">Erro ao buscar dados: ' . $conn->error . '</td></tr>';
                    } else if ($result->num_rows == 0) {
                        echo '<tr><td colspan="4" class="text-center">Nenhum momento cadastrado.</td></tr>';
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['idMomento'] . '</td>';
                            echo '<td>' . htmlspecialchars($row['DescMomento']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['OrdemDeExecucao']) . '</td>';
                            echo '<td>';
                            echo '<a href="editar.php?id=' . $row['idMomento'] . '" class="btn btn-sm btn-warning me-2">Editar</a>';
                            echo '<a href="?excluir=' . $row['idMomento'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Tem certeza que deseja excluir este momento?\')">Excluir</a>';
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
