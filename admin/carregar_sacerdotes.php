<?php
include_once("../conexao.php");
$query = $conn->query("SELECT idSacerdote, NomeSacerdote FROM tbsacerdotes ORDER BY NomeSacerdote");
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='{$row['idSacerdote']}'>{$row['NomeSacerdote']}</option>";
}
