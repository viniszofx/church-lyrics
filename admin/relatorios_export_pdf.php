<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once('../conexao.php');

// Filtros
$filtro_musica = $_GET['musica'] ?? '';
$filtro_tom = $_GET['tom'] ?? '';
$filtro_momento = $_GET['momento'] ?? '';

// Consulta
$sql = "
SELECT mu.NomeMusica, ci.TomMusica, mo.DescMomento, ci.DescMusicaCifra, vi.linkVideo
FROM tbmusica mu
LEFT JOIN tbMusicaMomentoMissa mm ON mm.idMusica = mu.idMusica
LEFT JOIN tbMomentosMissa mo ON mo.idMomento = mm.idMomento
LEFT JOIN tbCifras cm ON cm.idMusica = mu.idMusica
LEFT JOIN tbcifras ci ON ci.idCifra = cm.idCifra
LEFT JOIN tbvideo vi ON vi.idMusica = mu.idMusica
WHERE 1=1
";

if ($filtro_musica) $sql .= " AND mu.NomeMusica LIKE '%$filtro_musica%'";
if ($filtro_tom) $sql .= " AND ci.TomMusica LIKE '%$filtro_tom%'";
if ($filtro_momento) $sql .= " AND mo.idMomento = '$filtro_momento'";

$sql .= " GROUP BY mu.idMusica ORDER BY mu.NomeMusica";
$res = $conn->query($sql);

// Montar HTML para PDF
$html = '<h2 style="text-align:center;">Relatório de Músicas</h2>';
$html .= '<table border="1" cellpadding="6" cellspacing="0" width="100%">';
$html .= '<thead><tr style="background:#eee;">
            <th>Nome da Música</th>
            <th>Tom</th>
            <th>Momento</th>
            <th>Cifra</th>
            <th>Vídeo</th>
          </tr></thead><tbody>';

while ($row = $res->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($row['NomeMusica']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['TomMusica']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['DescMomento']) . '</td>';
    $html .= '<td style="white-space:pre-wrap;">' . htmlspecialchars($row['DescMusicaCifra']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['linkVideo']) . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody></table>';

// Gerar PDF com mPDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('relatorio_musicas.pdf', 'I');
?>
