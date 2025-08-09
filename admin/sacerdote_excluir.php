<?php
include_once("../conexao.php");

$id = $_GET['id'];

// Verifica vínculo com igreja
$res = $conn->query("SELECT COUNT(*) AS total FROM tbIgrejaSacerdote WHERE idSacerdote = $id");
$qtd = $res->fetch_assoc()['total'];

if ($qtd > 0) {
    $msg = "Não é possível excluir. Sacerdote está vinculado a $qtd igreja(s).";
    header("Location: sacerdote_list.php?erro=" . urlencode($msg));
    exit;
}

// Pode excluir
$conn->query("DELETE FROM tbSacerdotes WHERE idSacerdote = $id");
header("Location: sacerdote_list.php?ok=1");
