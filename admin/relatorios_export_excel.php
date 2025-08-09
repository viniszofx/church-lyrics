<?php
include_once('../conexao.php');

// Definir headers para download do Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=relatorio_musicas.xls");

// Filtros
$filtro_musica = $_GET['musica'] ?? '';
$filtro_tom = $_GET['tom'] ?? '';
$filtro_momento = $_GET['momento'] ?? '';

// Montar consulta
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

// Criar tabela para Excel
echo "<table border='1'>";
echo "<tr><th>Nome da Música</th><th>Tom</th><th>Momento</th><th>Cifra</th><th>Vídeo</th></tr>";
while ($row = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['NomeMusica']) . "</td>";
    echo "<td>" . htmlspecialchars($row['TomMusica']) . "</td>";
    echo "<td>" . htmlspecialchars($row['DescMomento']) . "</td>";
    echo "<td>" . nl2br(htmlspecialchars($row['DescMusicaCifra'])) . "</td>";
    echo "<td>" . htmlspecialchars($row['linkVideo']) . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
