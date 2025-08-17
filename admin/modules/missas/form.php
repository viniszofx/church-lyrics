<?php
include_once('../../includes/header.php');

// Buscar igrejas para o dropdown
$igrejas_query = "SELECT idIgreja, NomeIgreja FROM tbigreja ORDER BY NomeIgreja";
$igrejas = $conn->query($igrejas_query);

if (!$igrejas) {
    echo '<div class="alert alert-danger">Erro ao buscar igrejas: ' . $conn->error . '</div>';
}

// Buscar datas comemorativas para o dropdown
$datas_query = "SELECT idDataComemorativa, DescComemoracao FROM tbdatacomemorativa ORDER BY DescComemoracao";
$datas = $conn->query($datas_query);

if (!$datas) {
    echo '<div class="alert alert-danger">Erro ao buscar datas comemorativas: ' . $conn->error . '</div>';
}

// Buscar momentos da missa para o dropdown
$momentos_query = "SELECT idMomento, DescMomento FROM tbmomentosmissa ORDER BY OrdemDeExecucao";
$momentos = $conn->query($momentos_query);

if (!$momentos) {
    echo '<div class="alert alert-danger">Erro ao buscar momentos da missa: ' . $conn->error . '</div>';
}

// Buscar músicas para o dropdown
$musicas_query = "SELECT idMusica, NomeMusica FROM tbmusica ORDER BY NomeMusica";
$musicas = $conn->query($musicas_query);

if (!$musicas) {
    echo '<div class="alert alert-danger">Erro ao buscar músicas: ' . $conn->error . '</div>';
}

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do formulário
    $titulo = mysqli_real_escape_string($conn, $_POST['TituloMissa']);
    $data = mysqli_real_escape_string($conn, $_POST['DataMissa']);
    $ano = mysqli_real_escape_string($conn, $_POST['AnoMissa']);
    $idIgreja = isset($_POST['idIgreja']) ? intval($_POST['idIgreja']) : null;
    $idDataComemorativa = isset($_POST['idDataComemorativa']) ? intval($_POST['idDataComemorativa']) : null;
    $status = isset($_POST['Status']) ? 1 : 0;
    $momentos_musicas = isset($_POST['momentos']) ? $_POST['momentos'] : [];

    // Iniciar transação
    $conn->begin_transaction();
    
    try {
        // Inserir missa
        $sql = "INSERT INTO tbmissa (DataMissa, AnoMissa, TituloMissa, idIgreja, idDataComemorativa, Status) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }
        
        $stmt->bind_param("sssiii", $data, $ano, $titulo, $idIgreja, $idDataComemorativa, $status);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao salvar missa: " . $stmt->error);
        }
        
        $idMissa = $conn->insert_id;
        $stmt->close();
        
        // Inserir momentos e músicas
        if (!empty($momentos_musicas)) {
            foreach ($momentos_musicas as $idMomento => $idMusicas) {
                if (!is_array($idMusicas)) continue;
                
                foreach ($idMusicas as $idMusica) {
                    $sql = "INSERT INTO tbmissamomentomusica (idMissa, idMomento, idMusica) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    
                    if (!$stmt) {
                        throw new Exception("Erro na preparação da consulta: " . $conn->error);
                    }
                    
                    $stmt->bind_param("iii", $idMissa, $idMomento, $idMusica);
                    
                    if (!$stmt->execute()) {
                        throw new Exception("Erro ao vincular música: " . $stmt->error);
                    }
                    
                    $stmt->close();
                }
            }
        }
        
        // Confirmar transação
        $conn->commit();
        
        echo '<div class="alert alert-success">Missa cadastrada com sucesso!</div>';
        echo '<script>setTimeout(function() { window.location.href = "list.php"; }, 2000);</script>';
        
    } catch (Exception $e) {
        // Reverter transação em caso de erro
        $conn->rollback();
        echo '<div class="alert alert-danger">Erro: ' . $e->getMessage() . '</div>';
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Cadastrar Nova Missa</h2>
        <a href="list.php" class="btn btn-secondary">Voltar à lista</a>
    </div>

    <form method="post" class="needs-validation" novalidate>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Informações Gerais</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="TituloMissa" class="form-label">Título da Missa *</label>
                        <input type="text" class="form-control" id="TituloMissa" name="TituloMissa" required>
                        <div class="invalid-feedback">
                            Por favor, informe o título da missa.
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="DataMissa" class="form-label">Data *</label>
                        <input type="date" class="form-control" id="DataMissa" name="DataMissa" required>
                        <div class="invalid-feedback">
                            Por favor, selecione a data.
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="AnoMissa" class="form-label">Ano Litúrgico *</label>
                        <input type="text" class="form-control" id="AnoMissa" name="AnoMissa" 
                               placeholder="Ex: A, B ou C" maxlength="1" required>
                        <div class="invalid-feedback">
                            Por favor, informe o ano litúrgico.
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="idIgreja" class="form-label">Igreja</label>
                        <select class="form-select" id="idIgreja" name="idIgreja">
                            <option value="">Selecione uma igreja</option>
                            <?php 
                            if ($igrejas) {
                                while ($igreja = $igrejas->fetch_assoc()): ?>
                                    <option value="<?= $igreja['idIgreja'] ?>">
                                        <?= htmlspecialchars($igreja['NomeIgreja']) ?>
                                    </option>
                                <?php endwhile;
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="idDataComemorativa" class="form-label">Data Comemorativa</label>
                        <select class="form-select" id="idDataComemorativa" name="idDataComemorativa">
                            <option value="">Selecione uma data comemorativa</option>
                            <?php 
                            if ($datas) {
                                while ($data = $datas->fetch_assoc()): ?>
                                    <option value="<?= $data['idDataComemorativa'] ?>">
                                        <?= htmlspecialchars($data['DescComemoracao']) ?>
                                    </option>
                                <?php endwhile;
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="Status" name="Status" checked>
                            <label class="form-check-label" for="Status">
                                Missa Ativa
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Seleção de Músicas por Momento</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="momentosAccordion">
                    <?php 
                    if ($momentos) {
                        while ($momento = $momentos->fetch_assoc()): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $momento['idMomento'] ?>">
                                    <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse<?= $momento['idMomento'] ?>" 
                                            aria-expanded="false" 
                                            aria-controls="collapse<?= $momento['idMomento'] ?>">
                                        <?= htmlspecialchars($momento['DescMomento']) ?>
                                    </button>
                                </h2>
                                <div id="collapse<?= $momento['idMomento'] ?>" 
                                     class="accordion-collapse collapse" 
                                     aria-labelledby="heading<?= $momento['idMomento'] ?>" 
                                     data-bs-parent="#momentosAccordion">
                                    <div class="accordion-body">
                                        <div class="mb-2">
                                            <label class="form-label">Selecione as músicas para este momento:</label>
                                            <?php 
                                            if ($musicas) {
                                                // Reset the pointer to the beginning of the result set
                                                $musicas->data_seek(0);
                                                while ($musica = $musicas->fetch_assoc()): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               id="musica_<?= $momento['idMomento'] ?>_<?= $musica['idMusica'] ?>" 
                                                               name="momentos[<?= $momento['idMomento'] ?>][]" 
                                                               value="<?= $musica['idMusica'] ?>">
                                                        <label class="form-check-label" 
                                                               for="musica_<?= $momento['idMomento'] ?>_<?= $musica['idMusica'] ?>">
                                                            <?= htmlspecialchars($musica['NomeMusica']) ?>
                                                        </label>
                                                    </div>
                                                <?php endwhile;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-save"></i> Salvar Missa
            </button>
            <a href="list.php" class="btn btn-secondary btn-lg">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<script>
// Script para validação de formulário
(function () {
  'use strict'

  // Fetch all forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
</script>

<?php include_once('../../includes/footer.php'); ?>
