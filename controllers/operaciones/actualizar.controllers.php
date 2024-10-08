<?php
require_once '../../models/Conexion.php';

if (isset($_POST['idoperacion']) && isset($_POST['operacion']) && isset($_POST['precio'])) {
  $idoperacion = $_POST['idoperacion'];
  $operacion = $_POST['operacion'];
  $precio = $_POST['precio'];

  try {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();

    $sql = "UPDATE operaciones SET operacion = :operacion, precio = :precio WHERE idoperacion = :idoperacion";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idoperacion', $idoperacion, PDO::PARAM_INT);
    $stmt->bindParam(':operacion', $operacion, PDO::PARAM_STR);
    $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode(['status' => 'success']);
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Faltan datos.']);
}
