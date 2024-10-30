<?php

require_once '/xampp/htdocs/LinoFino/models/Conexion.php'; // Incluye la conexión a la base de datos

class Pagos
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function registrarPago($idpersona, $idoperacion, $prendas_realizadas)
    {
        try {
            $pdo = $this->conexion->getConexion();

            // Configura el manejo de errores de PDO
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparar la llamada al procedimiento almacenado
            $stmt = $pdo->prepare("CALL registrarPago(?, ?, ?)");
            $stmt->execute([$idpersona, $idoperacion, $prendas_realizadas]);

            // Obtener el resultado del procedimiento
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Manejar errores
            return ['error' => $e->getMessage()];
        }
    }



    // Método para obtener todos los pagos
    public function obtenerPagos()
    {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->query("SELECT p.idpago, 
                                        CONCAT(pe.nombres, ' ', pe.apepaterno, ' ', pe.apematerno) AS nombre_trabajador, 
                                        o.operacion, 
                                        p.prendas_realizadas, 
                                        p.precio_operacion, 
                                        p.total_pago, 
                                        p.fecha_pago 
                                 FROM pagos p
                                 JOIN personas pe ON p.idpersona = pe.idpersona
                                 JOIN operaciones o ON p.idoperacion = o.idoperacion
                                 ORDER BY p.fecha_pago DESC");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function buscarPersonas($searchTerm)
    {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("CALL buscarPersonas(?)");
            $stmt->execute([$searchTerm]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function buscarOperaciones($searchTerm)
    {
        try {
            $pdo = $this->conexion->getConexion();
            $stmt = $pdo->prepare("CALL buscarOperaciones(?)");
            $stmt->execute([$searchTerm]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
