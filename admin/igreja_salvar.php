<?php
include_once("../conexao.php");

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$endereco = $_POST['endereco'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$tipo = $_POST['tipo'] ?? '';

if ($id) {
    // Atualizar
    $stmt = $conn->prepare("UPDATE tbIgreja SET NomeIgreja = ?, Endereco = ?, Telefone = ?, Tipo = ? WHERE idIgreja = ?");
    $stmt->bind_param("ssssi", $nome, $endereco, $telefone, $tipo, $id);
} else {
    // Inserir novo
    $stmt = $conn->prepare("INSERT INTO tbIgreja (NomeIgreja, Endereco, Telefone, Tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $endereco, $telefone, $tipo);
}

if ($stmt->execute()) {
    header("Location: igreja_list.php?ok=1");
} else {
    header("Location: igreja_list.php?erro=" . urlencode("Erro ao salvar registro."));
}
