<?php
include('../conexao.php');

// Filtros
$nomeMusica = $_GET['nome'] ?? '';
$idTpLiturgico = $_GET['tpliturgico'] ?? '';
$idMomento = $_GET['momento'] ?? '';

// Query para dropdowns
$tpliturgico_result = mysqli_query($conn, "SELECT idTpLiturgico, DescTempo FROM tbtpliturgico ORDER BY DescTempo");
$momentos_result = mysqli_query($conn, "SELECT idMomento, DescMomento FROM tbmomentosmissa ORDER BY DescMomento");

// Query principal
$sql = 
    "SELECT  
        m.idMusica, m.NomeMusica, 
        ANY_VALUE(mm.DescMomento) as DescMomento, 
        ANY_VALUE(mm.idMomento) as idMomento, 
        ANY_VALUE(tl.DescTempo) as DescTempo, 
        ANY_VALUE(tl.idTpLiturgico) as idTpLiturgico, 
        ANY_VALUE(tl.Sigla) as Sigla,
        COUNT(c.idCifra) as QtdeCifras
    FROM tbmusica m
    LEFT JOIN tbmusicamomentomissa mmm ON m.idMusica = mmm.idMusica
    LEFT JOIN tbmomentosmissa mm ON mmm.idMomento = mm.idMomento
    LEFT JOIN tbcifras c ON m.idMusica = c.idMusica
    LEFT JOIN tbtempomusica tm ON m.idMusica = tm.idMusica
    LEFT JOIN tbtpliturgico tl ON tm.idTpLiturgico = tl.idTpLiturgico
    WHERE 1 = 1";
    /*
    SUBSTITUIDO PELO SELECT ACIMA
    "SELECT m.idMusica, m.NomeMusica FROM tbmusica m
    LEFT JOIN tbtpliturgico t ON m.idTpLiturgico = t.idTpLiturgico
    LEFT JOIN tbmusicamomentomissa mm ON m.idMusica = mm.idMusica
    WHERE 1=1";
    */

if ($nomeMusica) {
    $sql .= " AND m.NomeMusica LIKE '%" . mysqli_real_escape_string($conn, $nomeMusica) . "%'";
}
if ($idTpLiturgico) {
    $sql .= " AND m.idTpLiturgico = '" . intval($idTpLiturgico) . "'";
}
if ($idMomento) {
    $sql .= " AND mm.idMomento = '" . intval($idMomento) . "'";
}

$sql .= " GROUP BY m.idMusica ORDER BY ANY_VALUE(COALESCE(mm.OrdemDeExecucao, '9999')), m.NomeMusica ASC";

// Execute query with error handling
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo '<div class="alert alert-danger">Erro na consulta SQL: ' . mysqli_error($conn) . '<br>Query: ' . $sql . '</div>';
    $result = []; // Initialize as empty array to avoid errors later
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listagem de Cifras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .contador {
            font-weight: bold;
            color: green;
        }
        .top-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="container mt-4">

<div class="top-buttons">
    <a href="index.php" class="btn btn-secondary">Voltar para o menu principal</a>
</div>

<h3>Listagem de Cifras</h3>

<form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="nome" class="form-control" placeholder="Buscar por Nome da Música" value="<?= htmlspecialchars($nomeMusica) ?>">
    </div>
    <div class="col-md-3">
        <select name="tpliturgico" class="form-select">
            <option value="">Tempo Litúrgico</option>
            <?php while ($row = mysqli_fetch_assoc($tpliturgico_result)): ?>
                <option value="<?= $row['idTpLiturgico'] ?>" <?= ($row['idTpLiturgico'] == $idTpLiturgico) ? 'selected' : '' ?>>
                    <?= $row['DescTempo'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="momento" class="form-select">
            <option value="">Momento</option>
            <?php while ($row = mysqli_fetch_assoc($momentos_result)): ?>
                <option value="<?= $row['idMomento'] ?>" <?= ($row['idMomento'] == $idMomento) ? 'selected' : '' ?>>
                    <?= $row['DescMomento'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Música</th>
            <th>Qtde de Cifras</th>
            <th>Momento</th>
            <th>Tempo Litúrgico</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if (is_object($result) && mysqli_num_rows($result) > 0) {
            while ($musica = mysqli_fetch_assoc($result)) { 
        ?>
            <tr>
                <td><?= htmlspecialchars($musica['NomeMusica']) ?></td>
                <td class="text-center"><?= $musica['QtdeCifras'] ?></td>
                <td><?= htmlspecialchars($musica['DescMomento']) ?></td>
                <td><?= htmlspecialchars($musica['DescTempo']) ?> (<?= htmlspecialchars($musica['Sigla']) ?>)</td>

                <td class="text-nowrap">
                    <a href="cifras_form.php?idMusica=<?= $musica['idMusica'] ?>" class="btn btn-success btn-sm">Incluir</a>
                    <a href="cifras_acao.php?acao=visualizar&idMusica=<?= $musica['idMusica'] ?>" class="btn btn-info btn-sm">Visualizar</a>
                    <a href="cifras_acao.php?acao=editar&idMusica=<?= $musica['idMusica'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="cifras_acao.php?acao=excluir&idMusica=<?= $musica['idMusica'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir todas as cifras desta música?');">Excluir</a>
                </td>
            </tr>
            <?php 
            }
        } else {
            echo '<tr><td colspan="5" class="text-center">Nenhuma cifra encontrada.</td></tr>';
        }
        ?>
    </tbody>
</table>

</body>
</html>
