<?php
include '../conexao.php';

// Buscar momentos da missa
$momentos = mysqli_query($conn, "SELECT * FROM tbMomentosMissa ORDER BY DescMomento");

// Buscar tempos litúrgicos
$tempos = mysqli_query($conn, "SELECT * FROM tbTpLiturgico ORDER BY DescTpLiturgico");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Nova Música</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="mb-4">Cadastrar Nova Música</h2>

    <form action="musicas_salvar.php" method="post">
        <div class="mb-3">
            <label for="NomeMusica" class="form-label">Nome da Música</label>
            <input type="text" name="NomeMusica" id="NomeMusica" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tempo Litúrgico</label><br>
            <?php while($tp = mysqli_fetch_assoc($tempos)) { ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tempoLiturgico[]" value="<?= $tp['idTpLiturgico'] ?>" id="tp<?= $tp['idTpLiturgico'] ?>">
                    <label class="form-check-label" for="tp<?= $tp['idTpLiturgico'] ?>"><?= $tp['DescTpLiturgico'] ?></label>
                </div>
            <?php } ?>
        </div>

        <div class="mb-3">
            <label for="Musica" class="form-label">Música</label>
            <textarea name="Musica" id="Musica" rows="8" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Momentos da Missa</label><br>
            <?php while($momento = mysqli_fetch_assoc($momentos)) { ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="momentos[]" value="<?= $momento['idMomento'] ?>" id="mom<?= $momento['idMomento'] ?>">
                    <label class="form-check-label" for="mom<?= $momento['idMomento'] ?>"><?= $momento['DescMomento'] ?></label>
                </div>
            <?php } ?>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="musicas.php" class="btn btn-secondary">Voltar</a>
    </form>
</body>
</html>
