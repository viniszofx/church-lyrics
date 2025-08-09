<?php
include "conexao.php";

// Receber dados do formulário
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$nomeMusica = trim($_POST['NomeMusica']);
$musicaTexto = trim($_POST['Musica']);
$momentosSelecionados = isset($_POST['momentos']) ? $_POST['momentos'] : [];
$temposLiturgicos = isset($_POST['TpLiturgico']) ? $_POST['TpLiturgico'] : [];

// Evita espaços desnecessários
$nomeMusica = preg_replace('/^\s+|\s+$/u', '', $nomeMusica);

// Se for edição
if ($id > 0) {
    $stmt = $conn->prepare("UPDATE tbMusica SET NomeMusica = ?, Musica = ? WHERE idMusica = ?");
    $stmt->bind_param("ssi", $nomeMusica, $musicaTexto, $id);
    $stmt->execute();
    $stmt->close();

    // Atualiza tbMusicaMomentoMissa
    $conn->query("DELETE FROM tbMusicaMomentoMissa WHERE idMusica = $id");
    foreach ($momentosSelecionados as $idMomento) {
        $idMomento = intval($idMomento);
        $conn->query("INSERT INTO tbMusicaMomentoMissa (idMusica, idMomentoMissa) VALUES ($id, $idMomento)");
    }

    // Atualiza tbTempoMusica
    $conn->query("DELETE FROM tbTempoMusica WHERE idMusica = $id");
    foreach ($temposLiturgicos as $idTempo) {
        $idTempo = intval($idTempo);
        $conn->query("INSERT INTO tbTempoMusica (idMusica, idTpLiturgico) VALUES ($id, $idTempo)");
    }

} else {
    // Inserção de nova música
    $stmt = $conn->prepare("INSERT INTO tbMusica (NomeMusica, Musica) VALUES (?, ?)");
    $stmt->bind_param("ss", $nomeMusica, $musicaTexto);
    $stmt->execute();
    $idNovaMusica = $stmt->insert_id;
    $stmt->close();

    foreach ($momentosSelecionados as $idMomento) {
        $idMomento = intval($idMomento);
        $conn->query("INSERT INTO tbMusicaMomentoMissa (idMusica, idMomentoMissa) VALUES ($idNovaMusica, $idMomento)");
    }

    foreach ($temposLiturgicos as $idTempo) {
        $idTempo = intval($idTempo);
        $conn->query("INSERT INTO tbTempoMusica (idMusica, idTpLiturgico) VALUES ($idNovaMusica, $idTempo)");
    }
}

// Redireciona de volta à listagem
header("Location: musicas.php");
exit;
?>
