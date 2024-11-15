<?php

require_once '/xampp/htdocs/LinoFino/models/Conexion.php';

class Op {

    private $db;

    // Constructor que recibe la conexión de la base de datos
    public function __construct() {
        $this->db = (new Conexion())->getConexion();
    }

    // Método para buscar operaciones (OPs) según una consulta de texto
    public function buscarOps($query) {
        try {
            // Llamar al procedimiento almacenado 'spu_buscar_op'
            $stmt = $this->db->prepare("CALL spu_buscar_op(:query)");
            $stmt->bindParam(":query", $query);
            $stmt->execute();
            // Obtener todos los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Método para listar operaciones (OPs) que coincidan con la consulta de búsqueda
    public function listarOps($query) {
        try {
            // Llamar al procedimiento almacenado 'spu_listar_op'
            $stmt = $this->db->prepare("CALL spu_listar_op(:query)");
            $stmt->bindParam(":query", $query);
            $stmt->execute();
            // Obtener todos los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}


/* $op = new Op();

echo json_encode($op->buscarOps('2')); */
