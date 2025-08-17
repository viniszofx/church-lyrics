<?php
include('../conexao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Vínculos Igreja x Sacerdote</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <h3>Lista de Vínculos Igreja x Sacerdote</h3>
    <a href="vinculaigrejasacerdote_form.php" class="btn btn-success mb-3">Novo Vínculo</a>
    <a href="index.php" class="btn btn-secondary mb-3">Voltar ao Menu</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Igreja</th>
                <th>Sacerdote</th>
                <th>Data Início</th>
                <th>Data Fim</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT v.idIgrejaSacerdote, i.NomeIgreja, s.NomeSacerdote, v.DataInicio, v.DataFim, v.Status
                FROM tbigrejasacerdote v
                JOIN tbigreja i ON v.idIgreja = i.idIgreja
                JOIN tbsacerdotes s ON v.idSacerdote = s.idSacerdote";
        $result = $conn->query($sql);

        if (!$result) {
            echo '<div class="alert alert-danger">Erro na consulta SQL: ' . $conn->error . '</div>';
        } elseif ($result->num_rows == 0) {
            echo '<tr><td colspan="6" class="text-center">Nenhum vínculo encontrado</td></tr>';
        } else {
            while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['NomeIgreja']}</td>
                    <td>{$row['NomeSacerdote']}</td>
                    <td>{$row['DataInicio']}</td>
                    <td>{$row['DataFim']}</td>
                    <td>" . ($row['Status'] ? 'Ativo' : 'Inativo') . "</td>
                    <td>
                        <a href='vinculaigrejasacerdote_form.php?id={$row['idIgrejaSacerdote']}' class='btn btn-primary btn-sm'>Editar</a>
                        <a href='#' onclick=\"if(confirm('Deseja realmente excluir este vínculo?')) { window.location.href='vinculaigrejasacerdote_form.php?excluir={$row['idIgrejaSacerdote']}'; }\" class='btn btn-danger btn-sm'>Excluir</a>
                    </td>
                  </tr>";
            }
        }
        ?>
        </tbody>
    </table>

</body>
</html>
