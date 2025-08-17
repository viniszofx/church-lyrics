<?php
include_once("../../includes/header.php");
?>

<h2 class="mb-2">Listagem de Músicas</h2>
<a href="form.php" class="btn btn-success btn-sm mb-3">Cadastrar Nova Música</a>

<!-- FILTROS -->
<form method="GET" class="mb-4">
    <div class="row g-2">
        <div class="col-12">
            <input type="text" name="nome" class="form-control" placeholder="Nome da Música" value="<?= $_GET['nome'] ?? '' ?>">
        </div>
        <div class="col-12">
            <input type="text" name="trecho" class="form-control" placeholder="Trecho da Música" value="<?= $_GET['trecho'] ?? '' ?>">
        </div>
        <div class="col-12">
            <select name="momento" class="form-select">
                <option value="">Todos os Momentos</option>
                <?php
                $resMomento = $conn->query("SELECT DISTINCT DescMomento FROM tbmomentosmissa ORDER BY DescMomento");
                if (!$resMomento) {
                    echo "<option value=''>Erro ao carregar momentos: " . $conn->error . "</option>";
                } else {
                    while ($row = $resMomento->fetch_assoc()) {
                        $selected = (isset($_GET['momento']) && $_GET['momento'] == $row['DescMomento']) ? 'selected' : '';
                        echo "<option $selected>" . $row['DescMomento'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-12">
            <select name="tempo" class="form-select">
                <option value="">Todos os Tempos Litúrgicos</option>
                <?php
                $resTempo = $conn->query("SELECT DISTINCT DescTempo FROM tbtpliturgico ORDER BY DescTempo");
                if (!$resTempo) {
                    echo "<option value=''>Erro ao carregar tempos litúrgicos: " . $conn->error . "</option>";
                } else {
                    while ($row = $resTempo->fetch_assoc()) {
                        $selected = (isset($_GET['tempo']) && $_GET['tempo'] == $row['DescTempo']) ? 'selected' : '';
                        echo "<option $selected>" . $row['DescTempo'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>
    </div>
</form>

<style>
    .resumo-musica {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
    }
    .card {
        margin-bottom: 15px;
    }
</style>

<?php
// CONSTRUINDO SQL COM FILTROS
$sql = "
SELECT 
    mu.idMusica, mu.NomeMusica, mu.Musica, mm.DescMomento, mm.OrdemDeExecucao,  
    v.LinkVideo, v.autor, c.TomMusica, c.DescMusicaCifra, 
    tl.DescTempo, tl.Sigla
FROM tbmusica mu
LEFT OUTER JOIN tbmusicamomentomissa mmm ON mu.IdMusica = mmm.idMusica
LEFT OUTER JOIN tbmomentosmissa mm ON mmm.idMomento = mm.idMomento
LEFT OUTER JOIN tbvideo v ON mu.idMusica = v.idVideo
LEFT OUTER JOIN tbcifras c ON mu.idMusica = c.idCifra
LEFT OUTER JOIN tbtempomusica tm ON mu.idMusica = tm.idMusica
LEFT OUTER JOIN tbtpliturgico tl ON tm.idTpLiturgico = tl.idTpLiturgico
WHERE 1 = 1
";

if (!empty($_GET['nome'])) {
    $nome = $conn->real_escape_string($_GET['nome']);
    $sql .= " AND LOWER(mu.NomeMusica) LIKE LOWER('%$nome%')";
}

if (!empty($_GET['trecho'])) {
    $trecho = $conn->real_escape_string($_GET['trecho']);
    $sql .= " AND LOWER(mu.Musica) LIKE LOWER('%$trecho%')";
}

if (!empty($_GET['momento'])) {
    $momento = $conn->real_escape_string($_GET['momento']);
    $sql .= " AND LOWER(mm.DescMomento) = LOWER('$momento')";
}

if (!empty($_GET['tempo'])) {
    $tempo = $conn->real_escape_string($_GET['tempo']);
    $sql .= " AND LOWER(tl.DescTempo) = LOWER('$tempo')";
}

$sql .= " ORDER BY mm.OrdemDeExecucao, mu.NomeMusica";

$res = $conn->query($sql);

if (!$res) {
    echo '<div class="alert alert-danger">Erro na consulta SQL: ' . $conn->error . '<br>Query: ' . $sql . '</div>';
} elseif ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
?>
<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($row['NomeMusica']) ?></h5>
        <p><strong>Momento:</strong> <?= htmlspecialchars($row['DescMomento']) ?> | 
           <strong>Tempo Litúrgico:</strong> <?= htmlspecialchars($row['DescTempo']) ?></p>
        <div class="resumo-musica">
            <?= nl2br(htmlspecialchars($row['Musica'])) ?>
        </div>
        <div class="mt-3">
            <a href="visualizar.php?id=<?= $row['idMusica'] ?>" class="btn btn-secondary btn-sm">Visualizar</a>
            <a href="editar.php?id=<?= $row['idMusica'] ?>" class="btn btn-warning btn-sm">Editar</a>
            <a href="excluir.php?id=<?= $row['idMusica'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta música?')">Excluir</a>
        </div>
    </div>
</div>
<?php
    }
} else {
    echo "<p class='alert alert-warning'>Nenhuma música encontrada.</p>";
}
?>

<?php include_once("../../includes/footer.php"); ?>
