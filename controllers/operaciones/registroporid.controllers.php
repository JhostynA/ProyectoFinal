<?php
require_once '../../models/Conexion.php';

if (isset($_POST['idoperacion'])) {
  $idoperacion = $_POST['idoperacion'];

  try {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();

    $sql = "SELECT * FROM operaciones WHERE idoperacion = :idoperacion";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idoperacion', $idoperacion, PDO::PARAM_INT);
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
