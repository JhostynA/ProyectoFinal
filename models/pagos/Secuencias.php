<?php

require_once '/xampp/htdocs/LinoFino/models/Conexion.php';

class Secuencia {

    private $db;

    // Constructor que recibe la conexión de la base de datos
    public function __construct() {
        $this->db = (new Conexion())->getConexion();
    }

    // Método para buscar secuencias asociadas a una operación de producción (OP)
    public function buscarSecuenciasPorOp($opNombre) {
        try {
            // Llamar al procedimiento almacenado 'spu_buscar_secuencias_por_op'
            $stmt = $this->db->prepare("CALL spu_buscar_secuencias_por_op(:op_nombre)");
            $stmt->bindParam(":op_nombre", $opNombre);
            $stmt->execute();
            // Obtener todos los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Método para listar secuencias asociadas a una operación de producción (OP)
    public function listarSecuenciasPorOp($opNombre) {
        try {
            // Llamar al procedimiento almacenado 'spu_listar_secuencias_por_op'
            $stmt = $this->db->prepare("CALL spu_listar_secuencias_por_op(:op_nombre)");
            $stmt->bindParam(":op_nombre", $opNombre);
            $stmt->execute();
            // Obtener todos los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}




/* $secuencia = new Secuencia();

echo json_encode($secuencia->listarSecuenciasPorOp('2')); */