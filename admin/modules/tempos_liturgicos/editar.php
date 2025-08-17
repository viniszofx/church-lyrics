<?php
include_once('../../includes/header.php');

if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">ID não especificado</div>';
    echo '<a href="list.php" class="btn btn-secondary">Voltar</a>';
    include_once('../../includes/footer.php');
    exit;
}

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM tbtpliturgico WHERE idTpLiturgico = $id");

if (!$res) {
    echo '<div class="alert alert-danger">Erro ao consultar tempo litúrgico: ' . $conn->error . '</div>';
    echo '<a href="list.php" class="btn btn-secondary">Voltar</a>';
    include_once('../../includes/footer.php');
    exit;
}

if ($res->num_rows == 0) {
    echo '<div class="alert alert-danger">Registro não encontrado.</div>';
    echo '<a href="list.php" class="btn btn-secondary">Voltar</a>';
    include_once('../../includes/footer.php');
    exit;
}

$row = $res->fetch_assoc();

if (isset($_POST['salvar'])) {
    $desc = trim($_POST['DescTempo']);
    $sigla = trim($_POST['Sigla']);
    
    if (empty($desc) || empty($sigla)) {
        echo '<div class="alert alert-danger">Preencha todos os campos!</div>';
    } else {
        $stmt = $conn->prepare("UPDATE tbtpliturgico SET DescTempo=?, Sigla=? WHERE idTpLiturgico=?");
        if (!$stmt) {
            echo '<div class="alert alert-danger">Erro ao preparar atualização: ' . $conn->error . '</div>';
        } else {
            $stmt->bind_param("ssi", $desc, $sigla, $id);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Registro atualizado com sucesso!</div>';
                // Atualizar dados na tela após salvar
                $row['DescTempo'] = $desc;
                $row['Sigla'] = $sigla;
            } else {
                echo '<div class="alert alert-danger">Erro ao atualizar: ' . $stmt->error . '</div>';
            }
        }
    }
}
?>

<h2>Editar Tempo Litúrgico</h2>

<form method="post" class="border p-3">
    <div class="mb-3">
        <label class="form-label">Descrição</label>
        <input type="text" name="DescTempo" class="form-control" value="<?= htmlspecialchars($row['DescTempo']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Sigla</label>
        <input type="text" name="Sigla" class="form-control" maxlength="2" value="<?= htmlspecialchars($row['Sigla']) ?>" required>
    </div>
    <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
    <a href="list.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include_once('../../includes/footer.php'); ?>
