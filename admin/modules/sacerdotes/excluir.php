<?php
include_once("../../includes/header.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header("Location: list.php?erro=" . urlencode("ID inválido"));
    exit;
}

// Verifica vínculo com igreja
$res = $conn->query("SELECT COUNT(*) AS total FROM tbigrejasacerdote WHERE idSacerdote = $id");
if (!$res) {
    header("Location: list.php?erro=" . urlencode("Erro ao verificar vínculos: " . $conn->error));
    exit;
}

$qtd = $res->fetch_assoc()['total'];

if ($qtd > 0) {
    $msg = "Não é possível excluir. Sacerdote está vinculado a $qtd igreja(s).";
    header("Location: list.php?erro=" . urlencode($msg));
    exit;
}

// Pode excluir
$result = $conn->query("DELETE FROM tbsacerdotes WHERE idSacerdote = $id");
if (!$result) {
    header("Location: list.php?erro=" . urlencode("Erro ao excluir: " . $conn->error));
    exit;
}

header("Location: list.php?ok=1");

include_once("../../includes/footer.php");
