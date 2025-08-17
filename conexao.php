<?php
// In Docker environment, use the service name 'db' to connect
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
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>