<?php
// Try to connect to MySQL
$host = 'db';
$user = 'root';
$pass = '';
$dbname = 'bdfolhetodigital';

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
    echo "Conexão com o banco de dados bem-sucedida!<br>";
    
    // Show tables
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        echo "<h3>Tabelas no banco de dados:</h3>";
        echo "<ul>";
        while ($row = $result->fetch_array()) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "Erro ao listar tabelas: " . $conn->error;
    }
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
