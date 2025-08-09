<?php
include_once("../conexao.php");

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$funcao = $_POST['funcao'] ?? '';
$telefone = $_POST['telefone'] ?? '';

if ($id) {
    // Atualizar
    $stmt = $conn->prepare("UPDATE tbSacerdotes SET NomeSacerdote = ?, Funcao = ?, Telefone = ? WHERE idSacerdote = ?");
    $stmt->bind_param("sssi", $nome, $funcao, $telefone, $id);
} else {
    // Inserir novo
    $stmt = $conn->prepare("INSERT INTO tbSacerdotes (NomeSacerdote, Funcao, Telefone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $funcao, $telefone);
}

if ($stmt->execute()) {
    header("Location: sacerdote_list.php?ok=1");
} else {
    header("Location: sacerdote_list.php?erro=" . urlencode("Erro ao salvar registro."));
}
