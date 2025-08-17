<?php
include_once("../../conexao.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: list.php?erro=" . urlencode("ID inválido."));
    exit;
}

// Verificar vínculo com tbIgrejaSacerdote
$resVinculoSac = $conn->query("SELECT 1 FROM tbigrejasacerdote WHERE idIgreja = $id LIMIT 1");
if (!$resVinculoSac) {
    header("Location: list.php?erro=" . urlencode("Erro ao verificar vínculos com sacerdotes: " . $conn->error));
    exit;
}

// Verificar vínculo com tbMissa
$resVinculoMissa = $conn->query("SELECT 1 FROM tbmissa WHERE idIgreja = $id LIMIT 1");
if (!$resVinculoMissa) {
    header("Location: list.php?erro=" . urlencode("Erro ao verificar vínculos com missas: " . $conn->error));
    exit;
}

if ($resVinculoSac->num_rows > 0 || $resVinculoMissa->num_rows > 0) {
    $msg = "Impossível excluir: Igreja vinculada a sacerdote(s) ou missa(s).";
    header("Location: list.php?erro=" . urlencode($msg));
    exit;
}

// Sem vínculo, excluir
$result = $conn->query("DELETE FROM tbigreja WHERE idIgreja = $id");
if (!$result) {
    header("Location: list.php?erro=" . urlencode("Erro ao excluir: " . $conn->error));
    exit;
}

header("Location: list.php?ok=1");
