<?php
include_once("../conexao.php");
$query = $conn->query("SELECT idIgreja, NomeIgreja FROM tbIgreja ORDER BY NomeIgreja");
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='{$row['idIgreja']}'>{$row['NomeIgreja']}</option>";
}
