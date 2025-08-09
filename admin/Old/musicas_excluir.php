<?php
// musicas_excluir.php
include('../conexao.php');

$idMusica = $_GET['idMusica'] ?? 0;

// Verifica se o parâmetro id foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Monta a query para exclusão
    $sql = "DELETE FROM tbMusica WHERE idMusica = $id";

    // Executa a query
    if (mysqli_query($conn, $sql)) {
        // Se excluiu, redireciona para a listagem
        header("Location: musicas.php");
        exit();
    } else {
        echo "Erro ao excluir música: " . mysqli_error($conn);
    }
} else {
    echo "ID da música não informado.";
}
?>