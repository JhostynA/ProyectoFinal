<?php

require_once '../../models/Login.php';


class PagosModels {

  private $db;

  public function __construct() {
    $conexion = new Conexion();
    $this->db = $conexion->getConexion();
  }

  public function getClientesActivos() {
    $stmt = $this->db->prepare("SELECT idcliente, nombrecomercial FROM clientes WHERE inactive_at IS NULL");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getOPByCliente($idcliente) {
    $stmt = $this->db->prepare("SELECT idop, op FROM ordenesproduccion WHERE idcliente = :idcliente");
    $stmt->bindParam(':idcliente', $idcliente, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getNSByOP($idop) {
    $stmt = $this->db->prepare("SELECT iddetop, numSecuencia FROM detalleop WHERE idop = :idop");
    $stmt->bindParam(':idop', $idop, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function gePrSByDOP($iddetop) {
    $stmt = $this->db->prepare("
        SELECT 
            p.idpersona, 
            CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo 
        FROM produccion pr
        INNER JOIN personas p ON pr.idpersona = p.idpersona
        WHERE pr.iddetop = :iddetop
    ");
    $stmt->bindParam(':iddetop', $iddetop, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}