<?php
include('../conexao.php');

$idMusica = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idMusica <= 0) {
    echo "<script>alert('ID inválido.'); window.location.href='musicas.php';</script>";
    exit;
}

$sql = "SELECT * FROM tbmusica WHERE idMusica = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idMusica);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Música não encontrada.'); window.location.href='musicas.php';</script>";
    exit;
}

$musica = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Música</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h3>Visualização da Música</h3>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($musica['NomeMusica']) ?></h5>

            <p><strong>Momentos da Missa:</strong><br>
                <?php
                $momentos = $conn->query("
                    SELECT mo.DescMomento
                    FROM tbmusicamomentomissa mm
                    JOIN tbmomentosmissa mo ON mo.idMomento = mm.idMomento
                    WHERE mm.idMusica = $idMusica
                    ORDER BY mo.OrdemDeExecucao
                ");
                $listaMomentos = [];
                while ($m = $momentos->fetch_assoc()) $listaMomentos[] = $m['DescMomento'];
                echo implode(" | ", $listaMomentos);
                ?>
            </p>

            <p><strong>Tempos Litúrgicos:</strong><br>
                <?php
                $tempos = $conn->query("
                    SELECT tp.Sigla
                    FROM tbtempomusica tm
                    JOIN tbtpliturgico tp ON tp.idTpLiturgico = tm.idTpLiturgico
                    WHERE tm.idMusica = $idMusica
                    ORDER BY tp.Sigla
                ");
                $listaTempos = [];
                while ($t = $tempos->fetch_assoc()) $listaTempos[] = $t['Sigla'];
                echo implode(" | ", $listaTempos);
                ?>
            </p>

            <p><strong>Letra da Música:</strong><br>
                <pre style="white-space: pre-wrap"><?= htmlspecialchars($musica['Musica']) ?></pre>
            </p>

            <a href="musicas.php" class="btn btn-secondary">Voltar à listagem</a>
        </div>
    </div>
</body>
</html>
