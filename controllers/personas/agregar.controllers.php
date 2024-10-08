<?php
require_once '../../models/Conexion.php';

if (isset($_POST['apepaterno']) && isset($_POST['apematerno']) && isset($_POST['nombres'])) {
  $apepaterno = $_POST['apepaterno'];
  $apematerno = $_POST['apematerno'];
  $nombres = $_POST['nombres'];

  try {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();

    $sqlVerificar = "CALL VerificarPersona(:apepaterno, :apematerno, :nombres)";
    $stmtVerificar = $pdo->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':apepaterno', $apepaterno, PDO::PARAM_STR);
    $stmtVerificar->bindParam(':apematerno', $apematerno, PDO::PARAM_STR);
    $stmtVerificar->bindParam(':nombres', $nombres, PDO::PARAM_STR);
    $stmtVerificar->execute();
    
    $resultado = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
    
    $stmtVerificar->closeCursor();

    if ($resultado['existe'] > 0) {
      echo json_encode(['status' => 'error', 'message' => 'La persona ya está registrada.']);
    } else {
      $sqlInsertar = "INSERT INTO personas (apepaterno, apematerno, nombres) VALUES (:apepaterno, :apematerno, :nombres)";
      $stmtInsertar = $pdo->prepare($sqlInsertar);
      $stmtInsertar->bindParam(':apepaterno', $apepaterno, PDO::PARAM_STR);
      $stmtInsertar->bindParam(':apematerno', $apematerno, PDO::PARAM_STR);
      $stmtInsertar->bindParam(':nombres', $nombres, PDO::PARAM_STR);
      $stmtInsertar->execute();

      echo json_encode(['status' => 'success']);
    }
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
