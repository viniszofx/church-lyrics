<?php
include_once("../conexao.php");

$idIgreja = $_GET['idIgreja'] ?? null;
$idSacerdote = $_GET['idSacerdote'] ?? null;

if (!$idIgreja || !$idSacerdote) {
  header("Location: vinculo_list.php?erro=" . urlencode("Dados insuficientes para exclusão."));
  exit;
}

// Verifica se o vínculo existe
$stmt = $conn->prepare("SELECT * FROM tbIgrejaSacerdote WHERE idIgreja = ? AND idSacerdote = ?");
$stmt->bind_param("ii", $idIgreja, $idSacerdote);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  header("Location: vinculo_list.php?erro=" . urlencode("Vínculo não encontrado."));
  exit;
}

// Exclui
$stmt = $conn->prepare("DELETE FROM tbIgrejaSacerdote WHERE idIgreja = ? AND idSacerdote = ?");
$stmt->bind_param("ii", $idIgreja, $idSacerdote);
if ($stmt->execute()) {
  header("Location: vinculo_list.php?ok=1");
} else {
  header("Location: vinculo_list.php?erro=" . urlencode("Não foi possível excluir o vínculo."));
}
