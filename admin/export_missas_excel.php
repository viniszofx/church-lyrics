<?php
include_once("conexao.php");

// Cabeçalhos para Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=missas.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Consulta
$sql = "
SELECT m.DataMissa, m.AnoMissa, m.TituloMissa, m.Status, i.NomeIgreja, dc.DescComemoracao
FROM tbmissa m
LEFT JOIN tbigreja i ON i.idIgreja = m.idIgreja
LEFT JOIN tbdatacomemorativa dc ON dc.idDataComemorativa = m.idDataComemorativa
ORDER BY m.DataMissa DESC";
$result = $conn->query($sql);

// Saída
echo "Data\tAno\tStatus\tIgreja\tComemoração\tTítulo\n";
while ($r = $result->fetch_assoc()) {
    echo date('d/m/Y', strtotime($r['DataMissa'])) . "\t";
    echo $r['AnoMissa'] . "\t";
    echo ($r['Status'] ? 'Ativa' : 'Inativa') . "\t";
    echo $r['NomeIgreja'] . "\t";
    echo $r['DescComemoracao'] . "\t";
    echo $r['TituloMissa'] . "\n";
}
?>
