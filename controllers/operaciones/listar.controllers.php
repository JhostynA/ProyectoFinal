<?php
require_once '../../models/Conexion.php';

class ControladorDatos {

  public function listarDatos() {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();
    
    $sql = "SELECT * FROM operaciones";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

}

$controlador = new ControladorDatos();
$datos = $controlador->listarDatos();

echo json_encode($datos);

?>

