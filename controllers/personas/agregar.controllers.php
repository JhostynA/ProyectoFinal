<?php
require_once '../../models/Conexion.php';

if (isset($_POST['apellidos']) && isset($_POST['nombres']) && isset ($_POST['telefono']) && isset ($_POST['tipodoc']) && isset ($_POST['numdoc'])) {
  $apellidos = $_POST['apellidos'];
  $nombres = $_POST['nombres'];
  $telefono = $_POST['telefono'];
  $tipodoc = $_POST['tipodoc'];
  $numdoc = $_POST['numdoc'] ;

  try {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();

    $sqlVerificar = "CALL VerificarPersona(:apellidos, :nombres, :telefono, :tipodoc, :numdoc)";
    $stmtVerificar = $pdo->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
    $stmtVerificar->bindParam(':nombres', $nombres, PDO::PARAM_STR);
    $stmtVerificar->bindParam(':telefono', $telefono, PDO::PARAM_STR);
    $stmtVerificar->bindParam(':tipodoc', $tipodoc, PDO::PARAM_STR);
    $stmtVerificar->bindParam(':numdoc', $numdoc, PDO::PARAM_STR);
    $stmtVerificar->execute();
    
    $resultado = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
    
    $stmtVerificar->closeCursor();

    if ($resultado['existe'] > 0) {
      echo json_encode(['status' => 'error', 'message' => 'La persona ya está registrada.']);
    } else {
      $sqlInsertar = "INSERT INTO personas (apellidos, nombres, telefono, tipodoc, numdoc) VALUES (:apellidos, :nombres, :telefono, :tipodoc, :numdoc)";
      $stmtInsertar = $pdo->prepare($sqlInsertar);
      $stmtInsertar->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
      $stmtInsertar->bindParam(':nombres', $nombres, PDO::PARAM_STR);
      $stmtInsertar->bindParam(':telefono', $telefono, PDO::PARAM_STR);
      $stmtInsertar->bindParam(':tipodoc', $tipodoc, PDO::PARAM_STR);
      $stmtInsertar->bindParam(':numdoc', $numdoc, PDO::PARAM_STR);
      $stmtInsertar->execute();

      echo json_encode(['status' => 'success']);
    }
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
