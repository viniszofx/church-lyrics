<?php
include_once("../../includes/header.php");

// Carregar momentos da missa
$momentos = [];
$sqlMomentos = "SELECT * FROM tbmomentosmissa ORDER BY DescMomento ASC";
$resultMomentos = $conn->query($sqlMomentos);
if (!$resultMomentos) {
    echo '<div class="alert alert-danger">Erro ao buscar momentos: ' . $conn->error . '</div>';
} else {
    while ($row = $resultMomentos->fetch_assoc()) {
        $momentos[] = $row;
    }
}

// Buscar tempos litúrgicos
$tempos = [];
$sqlTempos = "SELECT * FROM tbtpliturgico ORDER BY DescTempo ASC";
$resultTempos = $conn->query($sqlTempos);
if (!$resultTempos) {
    echo '<div class="alert alert-danger">Erro ao buscar tempos litúrgicos: ' . $conn->error . '</div>';
} else {
    while ($row = $resultTempos->fetch_assoc()) {
        $tempos[] = $row;
    }
}
?>

<h3>Cadastrar Nova Música</h3>

<form action="salvar.php" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome da Música:</label>
        <input type="text" name="NomeMusica" id="nome" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="texto" class="form-label">Texto da Música:</label>
        <textarea name="Musica" id="texto" class="form-control" rows="6" required></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Momentos da Missa</label><br>
        <?php foreach ($momentos as $m): ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="momentos[]" value="<?= $m['idMomento'] ?>">
                <label class="form-check-label"><?= htmlspecialchars($m['DescMomento']) ?></label>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Tempos Litúrgicos</label><br>
        <?php foreach ($tempos as $t): ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="tempos[]" value="<?= $t['idTpLiturgico'] ?>">
                <label class="form-check-label"><?= htmlspecialchars($t['DescTempo']) ?></label>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary" id="salvarMusica">Salvar Música</button>
        <a href="list.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<style>
    textarea {
        max-height: 300px;
        overflow-y: auto;
    }
</style>

<script>
function desabilitarBotao(botao) {
    botao.disabled = true;
}

document.addEventListener('DOMContentLoaded', function() {
    // Adiciona evento ao botão após o carregamento do DOM
    document.getElementById('salvarMusica').addEventListener('click', function(event) {
        // Desabilita o botão para evitar múltiplos envios
        setTimeout(function() {
            desabilitarBotao(document.getElementById('salvarMusica'));
        }, 100);
    });
});
</script>

<?php include_once("../../includes/footer.php"); ?>
