<?php
require_once '../../models/Conexion.php';

if (isset($_POST['idpersona']) && isset($_POST['apepaterno']) && isset($_POST['apematerno']) && isset($_POST['nombres'])) {
  $idpersona = $_POST['idpersona'];
  $apepaterno = $_POST['apepaterno'];
  $apematerno = $_POST['apematerno'];
  $nombres = $_POST['nombres'];

  try {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();

    $sql = "UPDATE personas SET apepaterno = :apepaterno, apematerno = :apematerno, nombres = :nombres WHERE idpersona = :idpersona";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idpersona', $idpersona, PDO::PARAM_INT);
    $stmt->bindParam(':apepaterno', $apepaterno, PDO::PARAM_STR);
    $stmt->bindParam(':apematerno', $apematerno, PDO::PARAM_STR);
    $stmt->bindParam(':nombres', $nombres, PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode(['status' => 'success']);
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Faltan datos.']);
}
