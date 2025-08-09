<?php

require_once __DIR__ . '/vendor/autoload.php';
include_once("../conexao.php");

use \Mpdf\Mpdf;
$mpdf = new Mpdf();

$sql = "
SELECT m.DataMissa, m.AnoMissa, m.TituloMissa, m.Status, i.NomeIgreja, dc.DescComemoracao
FROM tbmissa m
LEFT JOIN tbigreja i ON i.idIgreja = m.idIgreja
LEFT JOIN tbdatacomemorativa dc ON dc.idDataComemorativa = m.idDataComemorativa
ORDER BY m.DataMissa DESC";
$result = $conn->query($sql);

$html = "<h2>Relatório de Missas</h2>";
$html .= "<table border='1' width='100%' style='border-collapse: collapse;'>
<thead>
<tr>
<th>Data</th><th>Ano</th><th>Status</th><th>Igreja</th><th>Comemoração</th><th>Título</th>
</tr>
</thead><tbody>";

while ($r = $result->fetch_assoc()) {
    $html .= "<tr>";
    $html .= "<td>" . date('d/m/Y', strtotime($r['DataMissa'])) . "</td>";
    $html .= "<td>" . $r['AnoMissa'] . "</td>";
    $html .= "<td>" . ($r['Status'] ? 'Ativa' : 'Inativa') . "</td>";
    $html .= "<td>" . $r['NomeIgreja'] . "</td>";
    $html .= "<td>" . $r['DescComemoracao'] . "</td>";
    $html .= "<td>" . $r['TituloMissa'] . "</td>";
    $html .= "</tr>";
}
$html .= "</tbody></table>";

$mpdf->WriteHTML($html);
$mpdf->Output("missas.pdf", "D");
?>
