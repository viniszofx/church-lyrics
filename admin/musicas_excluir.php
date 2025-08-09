<?php
include('../conexao.php');

$idMusica = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);

if ($idMusica <= 0) {
    echo "<script>alert('ID de música inválido.'); history.back();</script>";
    exit;
}

// Verifica se existem vínculos em tbCifras
$sql = "SELECT COUNT(*) as total FROM tbcifras WHERE idMusica = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idMusica);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
if ($result['total'] > 0) {
    echo "<script>alert('Não é possível excluir a música pois ela possui cifras vinculadas.'); history.back();</script>";
    exit;
}

// Verifica se existem vínculos em tbVideo
$sql = "SELECT COUNT(*) as total FROM tbvideo WHERE idMusica = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idMusica);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
if ($result['total'] > 0) {
    echo "<script>alert('Não é possível excluir a música pois ela possui vídeos vinculados.'); history.back();</script>";
    exit;
}

// Verifica se existem vínculos em tbMissaMusicas
$sql = "SELECT COUNT(*) as total FROM tbmissamusicas WHERE idMusica = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idMusica);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
if ($result['total'] > 0) {
    echo "<script>alert('Não é possível excluir a música pois ela está vinculada a missas.'); history.back();</script>";
    exit;
}

// Exclui da tbmusicamomentomissa
$stmt = $conn->prepare("DELETE FROM tbmusicamomentomissa WHERE idMusica = ?");
$stmt->bind_param("i", $idMusica);
$stmt->execute();

// Exclui da tbtempomusica
$stmt = $conn->prepare("DELETE FROM tbtempomusica WHERE idMusica = ?");
$stmt->bind_param("i", $idMusica);
$stmt->execute();

// Exclui da tbmusica
$stmt = $conn->prepare("DELETE FROM tbmusica WHERE idMusica = ?");
$stmt->bind_param("i", $idMusica);
$stmt->execute();

echo "<script>alert('Música excluída com sucesso.'); window.location.href='musicas.php';</script>";
exit;
?>
