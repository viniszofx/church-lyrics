<?php
include_once('../../includes/header.php');

// Verificar se o ID da música foi fornecido
if (!isset($_GET['idMusica']) || empty($_GET['idMusica'])) {
    echo '<div class="alert alert-danger">ID da música não fornecido.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    include_once('../../includes/footer.php');
    exit;
}

$idMusica = intval($_GET['idMusica']);

// Recupera nome da música
$sql = "SELECT NomeMusica FROM tbmusica WHERE idMusica = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    include_once('../../includes/footer.php');
    exit;
}

$stmt->bind_param('i', $idMusica);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<div class="alert alert-danger">Música não encontrada.</div>';
    echo '<div class="text-center mt-3"><a href="list.php" class="btn btn-primary">Voltar à lista</a></div>';
    $stmt->close();
    include_once('../../includes/footer.php');
    exit;
}

$musica = $result->fetch_assoc();
$stmt->close();

// Se foi enviado o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tom = mysqli_real_escape_string($conn, $_POST['TomMusica']);
    $desc = mysqli_real_escape_string($conn, $_POST['DescMusicaCifra']);
    $link = mysqli_real_escape_string($conn, $_POST['LinkSiteCifra']);

    $sql = "INSERT INTO tbcifras (idMusica, TomMusica, DescMusicaCifra, LinkSiteCifra) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo '<div class="alert alert-danger">Erro na preparação da consulta: ' . $conn->error . '</div>';
    } else {
        $stmt->bind_param('isss', $idMusica, $tom, $desc, $link);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Cifra cadastrada com sucesso!</div>';
            echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 2000);</script>';
        } else {
            echo '<div class="alert alert-danger">Erro ao cadastrar: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Incluir Cifra</h2>
        <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Nova Cifra para: <?= htmlspecialchars($musica['NomeMusica']) ?></h5>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="TomMusica" class="form-label">Tom</label>
                    <input type="text" name="TomMusica" id="TomMusica" class="form-control" required>
                    <small class="text-muted">Ex: C, Am, D, G, etc.</small>
                </div>
                
                <div class="mb-3">
                    <label for="DescMusicaCifra" class="form-label">Descrição da Cifra</label>
                    <textarea name="DescMusicaCifra" id="DescMusicaCifra" class="form-control" rows="10" required></textarea>
                    <small class="text-muted">
                        Dicas de formatação:<br>
                        - Use [nota]G[/nota] para destacar notas<br>
                        - Use [negrito]texto[/negrito] para textos em negrito
                    </small>
                </div>
                
                <div class="mb-3">
                    <label for="LinkSiteCifra" class="form-label">Link Externo (opcional)</label>
                    <input type="url" name="LinkSiteCifra" id="LinkSiteCifra" class="form-control">
                    <small class="text-muted">Link para a cifra em algum site externo</small>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="list.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../../includes/footer.php'); ?>
