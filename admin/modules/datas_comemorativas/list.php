<?php
include_once('../../includes/header.php');

// Inserção de nova data comemorativa
if (isset($_POST['inserir'])) {
    $nome = mysqli_real_escape_string($conn, $_POST['DescComemoracao']);
    $quando = mysqli_real_escape_string($conn, $_POST['QuandoAcontece']);
    $mesdia = mysqli_real_escape_string($conn, $_POST['MesDia']);
    
    $sql = "INSERT INTO tbdatacomemorativa (DescComemoracao, QuandoAcontece, MesDia) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param("sss", $nome, $quando, $mesdia);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Data comemorativa adicionada com sucesso!</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao cadastrar: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Exclusão de data comemorativa
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    
    $sql = "DELETE FROM tbdatacomemorativa WHERE idDataComemorativa = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo '<div class="alert alert-warning">Data comemorativa removida com sucesso.</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao excluir: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Consulta de datas comemorativas
$query = "SELECT * FROM tbdatacomemorativa ORDER BY QuandoAcontece DESC";
$datas = $conn->query($query);

if ($datas === false) {
    echo '<div class="alert alert-danger">Erro na consulta: ' . $conn->error . '</div>';
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Datas Comemorativas</h2>
        <a href="../../index.php" class="btn btn-secondary">Voltar ao menu principal</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Nova Data Comemorativa</h5>
            <form method="post">
                <div class="mb-3">
                    <label for="DescComemoracao" class="form-label">Nome Comemoração</label>
                    <input type="text" name="DescComemoracao" id="DescComemoracao" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="QuandoAcontece" class="form-label">Quando Acontece</label>
                    <input type="text" name="QuandoAcontece" id="QuandoAcontece" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="MesDia" class="form-label">Mês/Dia</label>
                    <input type="text" name="MesDia" id="MesDia" class="form-control" required>
                </div>
                <button type="submit" name="inserir" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Listagem de Datas Comemorativas</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Quando</th>
                            <th>Mês/Dia</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if ($datas && $datas->num_rows > 0) {
                        while ($d = $datas->fetch_assoc()): ?>
                        <tr>
                            <td><?= $d['idDataComemorativa'] ?></td>
                            <td><?= htmlspecialchars($d['DescComemoracao']) ?></td>
                            <td><?= htmlspecialchars($d['QuandoAcontece']) ?></td>
                            <td><?= htmlspecialchars($d['MesDia']) ?></td>
                            <td>
                                <a href="editar.php?id=<?= $d['idDataComemorativa'] ?>" class="btn btn-sm btn-warning me-2">Editar</a>
                                <a href="?excluir=<?= $d['idDataComemorativa'] ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja excluir esta data comemorativa?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; 
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Nenhuma data comemorativa encontrada</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
