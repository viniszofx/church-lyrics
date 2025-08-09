<?php
include_once("../conexao.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: igreja_list.php?erro=" . urlencode("ID inválido."));
    exit;
}

// Verificar vínculo com tbIgrejaSacerdote
$resVinculoSac = $conn->query("SELECT 1 FROM tbIgrejaSacerdote WHERE idIgreja = $id LIMIT 1");

// Verificar vínculo com tbMissa
$resVinculoMissa = $conn->query("SELECT 1 FROM tbMissa WHERE idIgreja = $id LIMIT 1");

if ($resVinculoSac->num_rows > 0 || $resVinculoMissa->num_rows > 0) {
    $msg = "Impossível excluir: Igreja vinculada a sacerdote(s) ou missa(s).";
    header("Location: igreja_list.php?erro=" . urlencode($msg));
    exit;
}

// Sem vínculo, excluir
$conn->query("DELETE FROM tbIgreja WHERE idIgreja = $id");
header("Location: igreja_list.php?ok=1");
