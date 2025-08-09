<?php
include_once("../conexao.php");

$idMomento = (int) ($_GET['momento'] ?? 0);

$sql = "
SELECT m.idMusica, m.NomeMusica, m.Musica
FROM tbMusicaMomentoMissa mm
JOIN tbMusica m ON m.idMusica = mm.idMusica
WHERE mm.idMomento = $idMomento
ORDER BY m.NomeMusica";
$res = $conn->query($sql);

foreach ($res as $mus) {
    $id = $mus['idMusica'] . '_' . $idMomento;
    echo '<div class="form-check mb-2">';
    echo '<input class="form-check-input" type="checkbox" name="musicas[]" value="' . $id . '" id="mus' . $id . '">';
    echo '<label class="form-check-label" for="mus' . $id . '"><strong>' . htmlspecialchars($mus['NomeMusica']) . '</strong></label>';
    echo '<div class="preview-musica text-muted small">' . nl2br(substr(htmlspecialchars($mus['Musica']), 0, 500)) . '</div>';
    echo '</div>';
}
?>
