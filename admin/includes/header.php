<?php
// Ensure connection to database
include_once("../../../conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Administração - Church Lyrics</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-3">
  <nav class="mb-3">
    <?php
    // Detect if we're in a module page
    $current_path = $_SERVER['PHP_SELF'];
    if (strpos($current_path, '/modules/') !== false) {
      // We're in a module page, so we need to go up two levels
      echo '<a href="../../index.php" class="btn btn-secondary btn-sm">⬅️ Voltar ao menu principal</a>';
    } else {
      // We're in the admin root or direct child of admin, just go up one level
      echo '<a href="../index.php" class="btn btn-secondary btn-sm">⬅️ Voltar ao menu principal</a>';
    }
    ?>
  </nav>
