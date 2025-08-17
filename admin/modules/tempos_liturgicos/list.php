<?php include_once('../../includes/header.php'); ?>

<h2>Cadastro - Tempos Litúrgicos</h2>

<!-- Formulário de inserção -->
<form method="post" class="border p-3 mb-4" action="">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Descrição do Tempo</label>
            <input type="text" name="DescTempo" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Sigla</label>
            <input type="text" name="Sigla" class="form-control" maxlength="2" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" name="inserir" class="btn btn-primary">Cadastrar</button>
        </div>
    </div>
</form>

<?php
// Inserção com segurança e validação
if (isset($_POST['inserir'])) {
    $desc = trim($_POST['DescTempo']);
    $sigla = trim($_POST['Sigla']);
    
    if (empty($desc) || empty($sigla)) {
        echo '<div class="alert alert-danger">Preencha todos os campos!</div>';
    } else {
        // Usando prepared statement para evitar injeção SQL
        $stmt = $conn->prepare("INSERT INTO tbtpliturgico (DescTempo, Sigla) VALUES (?, ?)");
        if (!$stmt) {
            echo '<div class="alert alert-danger">Erro ao preparar consulta: ' . $conn->error . '</div>';
        } else {
            $stmt->bind_param("ss", $desc, $sigla);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Registro inserido com sucesso!</div>';
            } else {
                echo '<div class="alert alert-danger">Erro ao inserir: ' . $stmt->error . '</div>';
            }
        }
    }
}

// Exclusão com segurança
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    
    // Verificar se o tempo litúrgico está em uso em tbtempomusica
    $check = $conn->query("SELECT COUNT(*) as total FROM tbtempomusica WHERE idTpLiturgico = $id");
    if (!$check) {
        echo '<div class="alert alert-danger">Erro ao verificar relacionamentos: ' . $conn->error . '</div>';
    } else {
        $result = $check->fetch_assoc();
        if ($result['total'] > 0) {
            echo '<div class="alert alert-danger">Não é possível excluir! Este tempo litúrgico está associado a músicas.</div>';
        } else {
            // Usando prepared statement para exclusão
            $stmt = $conn->prepare("DELETE FROM tbtpliturgico WHERE idTpLiturgico = ?");
            if (!$stmt) {
                echo '<div class="alert alert-danger">Erro ao preparar exclusão: ' . $conn->error . '</div>';
            } else {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success">Registro excluído com sucesso!</div>';
                } else {
                    echo '<div class="alert alert-danger">Erro ao excluir: ' . $stmt->error . '</div>';
                }
            }
        }
    }
}
?>

<!-- Tabela de listagem -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Sigla</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $res = $conn->query("SELECT * FROM tbtpliturgico ORDER BY idTpLiturgico ASC");
    if (!$res) {
        echo '<tr><td colspan="4" class="text-danger">Erro ao consultar tempos litúrgicos: ' . $conn->error . '</td></tr>';
    } else if ($res->num_rows == 0) {
        echo '<tr><td colspan="4" class="text-center">Nenhum tempo litúrgico cadastrado</td></tr>';
    } else {
        while ($row = $res->fetch_assoc()):
        ?>
            <tr>
                <td><?= $row['idTpLiturgico'] ?></td>
                <td><?= htmlspecialchars($row['DescTempo']) ?></td>
                <td><?= htmlspecialchars($row['Sigla']) ?></td>
                <td>
                    <a href="?excluir=<?= $row['idTpLiturgico'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmar exclusão?')">Excluir</a>
                    <a href="editar.php?id=<?= $row['idTpLiturgico'] ?>" class="btn btn-sm btn-warning">Editar</a>
                </td>
            </tr>
        <?php 
        endwhile; 
    }
    ?>
    </tbody>
</table>

<?php include_once('../../includes/footer.php'); ?>
