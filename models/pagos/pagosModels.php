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
        SELECT DISTINCT 
            p.idpersona, 
            CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo
        FROM 
            personas p
        INNER JOIN 
            produccion pr ON pr.idpersona = p.idpersona
        WHERE 
            pr.iddetop_operacion IN (
                SELECT id 
                FROM detalleop_operaciones 
                WHERE iddetop = :iddetop
            )
    ");
    
    $stmt->bindParam(':iddetop', $iddetop, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);   
}

public function getProduccionByPersona($idpersona) {
  $stmt = $this->db->prepare("
     SELECT 
    pr.fecha, 
    o.operacion AS operacion, 
    o.precio, 
    pr.cantidadproducida, 
    (o.precio * pr.cantidadproducida) AS total_pago, 
    pr.idpersona
FROM produccion pr
INNER JOIN detalleop_operaciones dop ON pr.iddetop_operacion = dop.id
INNER JOIN operaciones o ON dop.idoperacion = o.idoperacion
WHERE pr.idpersona = ? AND pr.pagado = FALSE;

  ");
  $stmt->execute([$idpersona]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function registrarPago($idModalidad, $idPersona, $fecha, $totalPago) {
  try {
      // Inicia una transacción
      $this->db->beginTransaction();

      // Primero, registramos el pago
      $sql = "INSERT INTO pagos (idmodalidad, idpersona, fecha, totalpago) VALUES (?, ?, ?, ?)";
      $stmt = $this->db->prepare($sql);
      
      if (!$stmt->execute([$idModalidad, $idPersona, $fecha, $totalPago])) {
          // Si falla la inserción, lanzamos un error y revertimos la transacción
          $this->db->rollBack();
          return ['success' => false, 'message' => 'Error al registrar el pago.'];
      }

      // Luego, actualizamos las producciones como pagadas
      $updateStmt = $this->db->prepare("
          UPDATE produccion
          SET pagado = TRUE, fechapagopersona = NOW()
          WHERE idpersona = ? AND pagado = FALSE
      ");
      $updateStmt->execute([$idPersona]);

      // Confirmamos la transacción
      $this->db->commit();

      return ['success' => true, 'message' => 'Pago registrado y producciones actualizadas exitosamente.'];
  } catch (Exception $e) {
      // Si ocurre algún error, revertimos la transacción
      $this->db->rollBack();
      return ['success' => false, 'message' => 'Error al procesar el pago y actualizar producciones: ' . $e->getMessage()];
  }
}





}