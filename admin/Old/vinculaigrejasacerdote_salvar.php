<?php
include('../conexao.php');

$idIgreja = isset($_POST['idIgreja']) ? (int)$_POST['idIgreja'] : null;
$idSacerdote = isset($_POST['idSacerdote']) ? (int)$_POST['idSacerdote'] : null;
$dataInicio = $_POST['DataInicio'];
$dataFim = $_POST['DataFim'] ?? null;
$status = $_POST['Status'];

if (!$idIgreja || !$idSacerdote) {
    header("Location: vinculaigrejasacerdote_list.php?erro=" . urlencode("IDs ausentes."));
    exit;
}

// Verifica se vínculo já existe
$sql = "SELECT * FROM tbIgrejaSacerdote WHERE idIgreja = ? AND idSacerdote = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idIgreja, $idSacerdote);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Atualiza
    $sql = "UPDATE tbIgrejaSacerdote SET DataInicio=?, DataFim=?, Status=? WHERE idIgreja=? AND idSacerdote=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiii", $dataInicio, $dataFim, $status, $idIgreja, $idSacerdote);
} else {
    // Insere
    $sql = "INSERT INTO tbIgrejaSacerdote (idIgreja, idSacerdote, DataInicio, DataFim, Status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissi", $idIgreja, $idSacerdote, $dataInicio, $dataFim, $status);
}

if ($stmt->execute()) {
    header("Location: vinculaigrejasacerdote_list.php");
} else {
    echo "Erro ao salvar: " . $conn->error;
}
?>
