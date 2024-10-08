<?php
require_once '../../models/Conexion.php';

if (isset($_POST['operacion']) && isset($_POST['precio'])) {
  $operacion = $_POST['operacion'];
  $precio = $_POST['precio'];

  try {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();

    $sqlVerificar = "CALL VerificarOperacion(:operacion, :precio)";
    $stmtVerificar = $pdo->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':operacion', $operacion, PDO::PARAM_STR);
    $stmtVerificar->bindParam(':precio', $precio, PDO::PARAM_STR);
    $stmtVerificar->execute();
    
    $resultado = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
    
    $stmtVerificar->closeCursor();

    if ($resultado['existe'] > 0) {
      echo json_encode(['status' => 'error', 'message' => 'La operación ya está registrada.']);
    } else {
      $sqlInsertar = "INSERT INTO operaciones (operacion, precio) VALUES (:operacion, :precio)";
      $stmtInsertar = $pdo->prepare($sqlInsertar);
      $stmtInsertar->bindParam(':operacion', $operacion, PDO::PARAM_STR);
      $stmtInsertar->bindParam(':precio', $precio, PDO::PARAM_STR);
      $stmtInsertar->execute();

      echo json_encode(['status' => 'success']);
    }
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
