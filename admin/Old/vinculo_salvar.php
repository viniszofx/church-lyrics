<?php
include_once("../conexao.php");

$idIgreja = $_POST['idIgreja'] ?? null;
$idSacerdote = $_POST['idSacerdote'] ?? null;
$dataInicio = !empty($_POST['dataInicio']) ? date('Y-m-d', strtotime($_POST['dataInicio'])) : null;
$dataFim = !empty($_POST['dataFim']) ? date('Y-m-d', strtotime($_POST['dataFim'])) : null;
$status = $_POST['status'] ?? null;

if (!isset($_GET['idIgreja']) || !isset($_GET['idSacerdote'])) {
    header("Location: vinculo_list.php?erro=" . urlencode("IDs ausentes."));
    exit;
}

/*
$idIgreja = (int)$_GET['idIgreja'];
$idSacerdote = (int)$_GET['idSacerdote'];
*/

if (!$idIgreja || !$idSacerdote || !$dataInicio || $status === null)

/*

if ($idIgreja <= 0 || $idSacerdote <= 0) {
    header("Location: vinculo_list.php?erro=" . urlencode("IDs inválidos."));
    exit;
}
*/

if (!$idIgreja || !$idSacerdote ) {
  header("Location: vinculo_list.php?erro=" . urlencode("Dados incompletos para salvar."));
  exit;
}


// Verifica se já existe
$stmt = $conn->prepare("SELECT * FROM tbIgrejaSacerdote WHERE idIgreja = ? AND idSacerdote = ?");
$stmt->bind_param("ii", $idIgreja, $idSacerdote);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
  // Atualizar
  $stmt = $conn->prepare("UPDATE tbIgrejaSacerdote SET DataInicio = ?, DataFim = ?, Status = ? WHERE idIgreja = ? AND idSacerdote = ?");
  $stmt->bind_param("ssiii", $dataInicio, $dataFim, $status, $idIgreja, $idSacerdote);
} else {
  // Inserir
  $stmt = $conn->prepare("INSERT INTO tbIgrejaSacerdote (idIgreja, idSacerdote, DataInicio, DataFim, Status) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("iissi", $idIgreja, $idSacerdote, $dataInicio, $dataFim, $status);
}

if ($stmt->execute()) {
  header("Location: vinculo_list.php?ok=1");
} else {
  header("Location: vinculo_list.php?erro=" . urlencode("Erro ao salvar o vínculo."));
}