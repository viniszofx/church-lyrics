<?php
$conn = new mysqli("0.0.0.0", "root", "", "bdfolhetodigital");
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);
?>