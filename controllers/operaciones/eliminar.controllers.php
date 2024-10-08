<?php
require_once '../../models/Conexion.php';

if (isset($_POST['idoperacion'])) {
  $idoperacion = $_POST['idoperacion'];

  try {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();

    // Consulta para eliminar el registro
    $sqlEliminar = "DELETE FROM operaciones WHERE idoperacion = :idoperacion";
    $stmtEliminar = $pdo->prepare($sqlEliminar);
    $stmtEliminar->bindParam(':idoperacion', $idoperacion, PDO::PARAM_INT);
    $stmtEliminar->execute();

    echo json_encode(['status' => 'success']);
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Falta el ID']);
}
