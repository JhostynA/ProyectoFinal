<?php
require_once '../../models/Conexion.php';

if (isset($_POST['idpersona']) && isset($_POST['apellidos']) && isset($_POST['nombres']) && isset($_POST['telefono']) && isset($_POST['tipodoc']) && isset($_POST['numdoc'])) {
  $idpersona = $_POST['idpersona'];
  $apellidos = $_POST['apellidos'];
  $nombres = $_POST['nombres'];
  $telefono = $_POST['telefono'];
  $tipodoc = $_POST['tipodoc'];
  $numdoc = $_POST['numdoc'];


  try {
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();

    $sql = "UPDATE personas SET apellidos = :apellidos, nombres = :nombres, telefono = :telefono, tipodoc = :tipodoc, numdoc = :numdoc WHERE idpersona = :idpersona";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idpersona', $idpersona, PDO::PARAM_INT);
    $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
    $stmt->bindParam(':nombres', $nombres, PDO::PARAM_STR);
    $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
    $stmt->bindParam(':tipodoc', $tipodoc, PDO::PARAM_STR);
    $stmt->bindParam(':numdoc', $numdoc, PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode(['status' => 'success']);
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Faltan datos.']);
}
