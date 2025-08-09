<?php
include('../conexao.php');

// Buscar momentos da missa
$momentos = [];
$sqlMomentos = "SELECT * FROM tbmomentosmissa ORDER BY DescMomento ASC";
$resultMomentos = $conn->query($sqlMomentos);
while ($row = $resultMomentos->fetch_assoc()) {
    $momentos[] = $row;
}

// Buscar tempos litúrgicos
$tempos = [];
$sqlTempos = "SELECT * FROM tbtpliturgico ORDER BY DescTempo ASC";
$resultTempos = $conn->query($sqlTempos);
while ($row = $resultTempos->fetch_assoc()) {
    $tempos[] = $row;
}
?>



<script>
function desabilitarBotao(botao) {  botao.disabled = true;  }

// Adiciona um evento de envio do formulário para desabilitar o botão após o envio
document.getElementById('salvarMusica').addEventListener('click', function(event) 
    { 
        // Evita o comportamento padrão do botão (envio do formulário)
        event.preventDefault();

        // Simula o envio do formulário (substitua pelo seu código PHP real)
        
        setTimeout(function() 
        {   // Após o envio simulado, desabilita o botão
            <button type="submit" class="btn btn-primary">Salvar Música</button>                                                       
            desabilitarBotao(document.getElementById('salvarMusica'));
        }, 
        1000); // Aguarda 1 segundo (simulando o processamento)
    });
</script>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Nova Música</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        textarea {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Cadastrar Nova Música</h3>
        <a href="musicas.php" class="btn btn-secondary">Voltar à Página Anterior</a>
    </div>

    <form action="musicas_salvar.php" method="POST">
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

        <div class="mt-4 d-flex justify-content-between">
            <a href="musicas.php" class="btn btn-secondary">Cancelar</a>
            <a href="musicas.php" class="btn btn-secondary">Sair</a>
         
<!--           <button type="submit" class="btn btn-primary">Salvar Música</button>   -->
             <button type="submit" class="btn btn-primary" onclick="desabilitarBotao(this)" id="salvarMusica">Salvar Música</button> 
        </div>
    </form>
</div>
</body>
</html>
