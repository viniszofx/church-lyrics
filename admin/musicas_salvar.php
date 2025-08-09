<?php
include('../conexao.php');

// Função auxiliar para redirecionar com mensagem
function redirecionar($msg) {
    echo "<script>alert('$msg'); history.back();</script>";
    exit;
}

// Coleta os dados do formulário
$idMusica = isset($_POST['idMusica']) ? intval($_POST['idMusica']) : null;
$NomeMusica = isset($_POST['NomeMusica']) ? trim($_POST['NomeMusica']) : '';
$Musica = isset($_POST['Musica']) ? trim($_POST['Musica']) : '';
$momentosSelecionados = isset($_POST['momentos']) ? $_POST['momentos'] : [];
$temposSelecionados = isset($_POST['tempos']) ? $_POST['tempos'] : [];

// Validação dos campos obrigatórios
$campos_erro = [];
if (empty($NomeMusica)) $campos_erro[] = 'Nome da Música';
if (empty($Musica)) $campos_erro[] = 'Letra da Música';

if (!empty($campos_erro)) {
    redirecionar("Por favor, preencha os seguintes campos obrigatórios: " . implode(', ', $campos_erro));
}

if ($idMusica) {
    // Atualizar
    $stmt = $conn->prepare("UPDATE tbmusica SET NomeMusica=?, Musica=? WHERE idMusica=?");
    $stmt->bind_param("ssi", $NomeMusica, $Musica, $idMusica);
    $stmt->execute();

    // Limpa e reinsere momentos
    $conn->query("DELETE FROM tbmusicamomentomissa WHERE idMusica = $idMusica");
    foreach ($momentosSelecionados as $idMomento) {
        $stmt = $conn->prepare("INSERT INTO tbmusicamomentomissa (idMusica, idMomento) VALUES (?, ?)");
        $stmt->bind_param("ii", $idMusica, $idMomento);
        $stmt->execute();
    }

    // Limpa e reinsere tempos litúrgicos
    $conn->query("DELETE FROM tbtempomusica WHERE idMusica = $idMusica");
    foreach ($temposSelecionados as $idTpLiturgico) {
        $stmt = $conn->prepare("INSERT INTO tbtempomusica (idTpLiturgico, idMusica) VALUES (?, ?)");
        $stmt->bind_param("ii", $idTpLiturgico, $idMusica);
        $stmt->execute();
    }

    redirecionar("Música atualizada com sucesso!");
} else {
    // Inserir
    $stmt = $conn->prepare("INSERT INTO tbmusica (NomeMusica, Musica) VALUES (?, ?)");
    $stmt->bind_param("ss", $NomeMusica, $Musica);
    $stmt->execute();
    $novoIdMusica = $conn->insert_id;

    // Insere momentos
    foreach ($momentosSelecionados as $idMomento) {
        $stmt = $conn->prepare("INSERT INTO tbmusicamomentomissa (idMusica, idMomento) VALUES (?, ?)");
        $stmt->bind_param("ii", $novoIdMusica, $idMomento);
        $stmt->execute();
    }

    // Insere tempos litúrgicos
    foreach ($temposSelecionados as $idTpLiturgico) {
        $stmt = $conn->prepare("INSERT INTO tbtempomusica (idTpLiturgico, idMusica) VALUES (?, ?)");
        $stmt->bind_param("ii", $idTpLiturgico, $novoIdMusica);
        $stmt->execute();
    }

    redirecionar("Música cadastrada com sucesso!");
}

?>
