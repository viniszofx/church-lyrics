    $sql = "ALTER TABLE tbcifras AUTO_INCREMENT = 1";

    if ($conn->query($sql) === TRUE) {
    echo "Auto incremento resetado com sucesso!";
    } else {
    echo "Erro ao resetar o auto incremento: " . $conn->error;
    }   