<?php

require_once '/xampp/htdocs/LinoFino/models/Conexion.php';

class Operacion {

    private $db;

    // Constructor que recibe la conexión de la base de datos
    public function __construct() {
        $this->db = (new Conexion())->getConexion();
    }

    // Método para buscar operaciones según una consulta de texto
    public function buscarOperaciones($query) {
        try {
            // Llamar al procedimiento almacenado 'spu_buscar_operaciones'
            $stmt = $this->db->prepare("CALL spu_buscar_operaciones(:query)");
            $stmt->bindParam(":query", $query);
            $stmt->execute();
            // Obtener todos los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Método para listar operaciones que coincidan con la consulta de búsqueda
    public function listarOperaciones($query) {
        try {
            // Llamar al procedimiento almacenado 'spu_listar_operaciones'
            $stmt = $this->db->prepare("CALL spu_listar_operaciones(:query)");
            $stmt->bindParam(":query", $query);
            $stmt->execute();
            // Obtener todos los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}


/* $operacion = new Operacion();

echo json_encode($operacion->listarOperaciones('c'));
 */
