<?php
include_once("../conexao.php");

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID não informado.</div>";
    exit;
}

$id = (int) $_GET['id'];

// Excluir missa e músicas relacionadas (ON DELETE CASCADE cuida disso)
$conn->query("DELETE FROM tbmissa WHERE idMissa = $id");

header("Location: missas.php");
exit;
?>
