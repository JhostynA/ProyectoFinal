<?php
require_once '../../models/Conexion.php';

if (isset($_POST['idpersona'])) {
  $idpersona = $_POST['idpersona'];

  try {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();

    $sql = "SELECT * FROM personas WHERE idpersona = :idpersona";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idpersona', $idpersona, PDO::PARAM_INT);
    $stmt->execute();
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
      echo json_encode(['status' => 'success', 'registro' => $registro]);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'No se encontro el registro']);
    }
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Falta el ID.']);
}
