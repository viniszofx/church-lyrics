<?php
include_once("../../includes/header.php");

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$funcao = $_POST['funcao'] ?? '';
$telefone = $_POST['telefone'] ?? '';

if ($id) {
    // Atualizar
    $stmt = $conn->prepare("UPDATE tbsacerdotes SET NomeSacerdote = ?, Funcao = ?, Telefone = ? WHERE idSacerdote = ?");
    if (!$stmt) {
        header("Location: list.php?erro=" . urlencode("Erro ao preparar consulta: " . $conn->error));
        exit;
    }
    $stmt->bind_param("sssi", $nome, $funcao, $telefone, $id);
} else {
    // Inserir novo
    $stmt = $conn->prepare("INSERT INTO tbsacerdotes (NomeSacerdote, Funcao, Telefone) VALUES (?, ?, ?)");
    if (!$stmt) {
        header("Location: list.php?erro=" . urlencode("Erro ao preparar consulta: " . $conn->error));
        exit;
    }
    $stmt->bind_param("sss", $nome, $funcao, $telefone);
}

if ($stmt->execute()) {
    header("Location: list.php?ok=1");
} else {
    header("Location: list.php?erro=" . urlencode("Erro ao salvar registro: " . $conn->error));
}

include_once("../../includes/footer.php");
