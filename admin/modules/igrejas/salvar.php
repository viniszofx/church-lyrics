<?php
include_once("../../conexao.php");

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$endereco = $_POST['endereco'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$tipo = $_POST['tipo'] ?? '';

if ($id) {
    // Atualizar
    $stmt = $conn->prepare("UPDATE tbigreja SET NomeIgreja = ?, Endereco = ?, Telefone = ?, Tipo = ? WHERE idIgreja = ?");
    if (!$stmt) {
        header("Location: list.php?erro=" . urlencode("Erro ao preparar consulta: " . $conn->error));
        exit;
    }
    $stmt->bind_param("ssssi", $nome, $endereco, $telefone, $tipo, $id);
} else {
    // Inserir novo
    $stmt = $conn->prepare("INSERT INTO tbigreja (NomeIgreja, Endereco, Telefone, Tipo) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        header("Location: list.php?erro=" . urlencode("Erro ao preparar consulta: " . $conn->error));
        exit;
    }
    $stmt->bind_param("ssss", $nome, $endereco, $telefone, $tipo);
}

if ($stmt->execute()) {
    header("Location: list.php?ok=1");
} else {
    header("Location: list.php?erro=" . urlencode("Erro ao salvar registro: " . $conn->error));
}
