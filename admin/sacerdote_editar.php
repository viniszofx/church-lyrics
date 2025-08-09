<?php
include_once("../conexao.php");

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM tbSacerdotes WHERE idSacerdote = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Sacerdote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Editar Sacerdote</h2>
    <form method="post" action="sacerdote_salvar.php">
        <input type="hidden" name="idSacerdote" value="<?= $row['idSacerdote'] ?>">
        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="NomeSacerdote" class="form-control" value="<?= $row['NomeSacerdote'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Função:</label>
            <input type="text" name="Funcao" class="form-control" value="<?= $row['Funcao'] ?>">
        </div>
        <div class="mb-3">
            <label>Telefone:</label>
            <input type="text" name="Telefone" class="form-control" value="<?= $row['Telefone'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="sacerdote_list.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>
