<?php
include_once("../../includes/header.php");

$idMusica = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idMusica <= 0) {
    echo '<div class="alert alert-danger">ID inválido.</div>';
    echo '<a href="list.php" class="btn btn-secondary">Voltar à listagem</a>';
    include_once("../../includes/footer.php");
    exit;
}

$sql = "SELECT * FROM tbmusica WHERE idMusica = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo '<div class="alert alert-danger">Erro ao preparar consulta: ' . $conn->error . '</div>';
    echo '<a href="list.php" class="btn btn-secondary">Voltar à listagem</a>';
    include_once("../../includes/footer.php");
    exit;
}
$stmt->bind_param("i", $idMusica);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="alert alert-danger">Música não encontrada.</div>';
    echo '<a href="list.php" class="btn btn-secondary">Voltar à listagem</a>';
    include_once("../../includes/footer.php");
    exit;
}

$musica = $result->fetch_assoc();
?>

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
            if ($momentos) {
                while ($m = $momentos->fetch_assoc()) $listaMomentos[] = $m['DescMomento'];
                echo implode(" | ", $listaMomentos);
            } else {
                echo "Erro ao buscar momentos: " . $conn->error;
            }
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
            if ($tempos) {
                while ($t = $tempos->fetch_assoc()) $listaTempos[] = $t['Sigla'];
                echo implode(" | ", $listaTempos);
            } else {
                echo "Erro ao buscar tempos litúrgicos: " . $conn->error;
            }
            ?>
        </p>

        <p><strong>Letra da Música:</strong><br>
            <pre style="white-space: pre-wrap"><?= htmlspecialchars($musica['Musica']) ?></pre>
        </p>

        <div class="mt-3">
            <a href="list.php" class="btn btn-secondary">Voltar à listagem</a>
            <a href="editar.php?id=<?= $idMusica ?>" class="btn btn-warning">Editar</a>
        </div>
    </div>
</div>

<?php include_once("../../includes/footer.php"); ?>
