<?php
include_once("../conexao.php");

$idSacerdote = $_POST['idSacerdote'];
$igrejas = $_POST['igrejas'] ?? [];

// Remove vínculos existentes
$conn->query("DELETE FROM tbIgrejaSacerdote WHERE idSacerdote = $idSacerdote");

// Insere novos vínculos
foreach ($igrejas as $idIgreja) {
    $stmt = $conn->prepare("INSERT INTO tbIgrejaSacerdote (idIgreja, idSacerdotes, DataInicio, Status) VALUES (?, ?, NOW(), 1)");
    $stmt->bind_param("ii", $idIgreja, $idSacerdote);
    $stmt->execute();
}

header("Location: sacerdote_list.php");
