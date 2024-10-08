<?php
require_once '../../models/Conexion.php';

if (isset($_POST['idpersona'])) {
  $idpersona = $_POST['idpersona'];

  try {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();

    // Consulta para eliminar el registro
    $sqlEliminar = "DELETE FROM personas WHERE idpersona = :idpersona";
    $stmtEliminar = $pdo->prepare($sqlEliminar);
    $stmtEliminar->bindParam(':idpersona', $idpersona, PDO::PARAM_INT);
    $stmtEliminar->execute();

    echo json_encode(['status' => 'success']);
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Falta el ID']);
}
