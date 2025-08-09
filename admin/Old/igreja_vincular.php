<?php
include_once("../conexao.php");
$idSacerdote = $_GET['idSacerdote'];

$sacerdote = $conn->query("SELECT NomeSacerdote FROM tbSacerdotes WHERE idSacerdote = $idSacerdote")->fetch_assoc();
$igrejas = $conn->query("SELECT * FROM tbIgreja");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Vincular Igrejas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

    <h2>Vincular Igrejas a: <?= $sacerdote['NomeSacerdote'] ?></h2>
    <form action="igreja_vincular_salvar.php" method="post">
        <input type="hidden" name="idSacerdote" value="<?= $idSacerdote ?>">

        <?php while ($i = $igrejas->fetch_assoc()): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="igrejas[]" value="<?= $i['idIgreja'] ?>" id="ig<?= $i['idIgreja'] ?>">
                <label class="form-check-label" for="ig<?= $i['idIgreja'] ?>">
                    <?= $i['NomeIgreja'] ?>
                </label>
            </div>
        <?php endwhile; ?>

        <button type="submit" class="btn btn-primary mt-3">Salvar Vínculos</button>
        <a href="sacerdote_list.php" class="btn btn-secondary">Cancelar</a>    </form>
</body>
</html>
