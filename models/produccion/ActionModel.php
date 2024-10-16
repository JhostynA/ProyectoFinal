<?php

require_once '../../models/Login.php';

class ActionModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    public function createAction($nombre, $fecha_inicio, $fecha_entrega, $cantidad_prendas) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM actions WHERE nombre = :nombre");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            return false;
        }
    
        $stmt = $this->db->prepare("INSERT INTO actions (nombre, fecha_inicio, fecha_entrega, cantidad_prendas) VALUES (:nombre, :fecha_inicio, :fecha_entrega, :cantidad_prendas)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_entrega', $fecha_entrega);
        $stmt->bindParam(':cantidad_prendas', $cantidad_prendas);
        return $stmt->execute();
    }  

    public function getActions() {
        $stmt = $this->db->query("SELECT * FROM actions ORDER BY created_at DESC;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActionById($id) {
        $stmt = $this->db->prepare("SELECT * FROM actions WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSecuenciasByActionId($actionId) {
        $query = "SELECT * FROM secuencias WHERE idop = :actionId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':actionId', $actionId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
    
    public function getSecuenciaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM secuencias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getTallasBySecuenciaId($id) {
        $stmt = $this->db->prepare("SELECT * FROM tallas WHERE secuencia_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
    
    public function createSequence($idop, $numSecuencia, $fechaInicio, $fechaFinal, $prendasArealizar) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM secuencias WHERE idop = :idop AND numSecuencia = :numSecuencia");
        $stmt->bindParam(':idop', $idop);
        $stmt->bindParam(':numSecuencia', $numSecuencia);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            return false; 
        }
    
        $prendasFaltantes = $prendasArealizar;
    
        $stmt = $this->db->prepare("INSERT INTO secuencias (idop, numSecuencia, fechaInicio, fechaFinal, prendasArealizar, prendasFaltantes) VALUES (:idop, :numSecuencia, :fechaInicio, :fechaFinal, :prendasArealizar, :prendasFaltantes)");
        $stmt->bindParam(':idop', $idop);
        $stmt->bindParam(':numSecuencia', $numSecuencia);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFinal', $fechaFinal);
        $stmt->bindParam(':prendasArealizar', $prendasArealizar);
        $stmt->bindParam(':prendasFaltantes', $prendasFaltantes);
    
        return $stmt->execute();
    }
    

    public function getLastInsertedSequenceId() {
        return $this->db->lastInsertId();
    }
    
    public function createTalla($secuenciaId, $talla, $cantidad) {
        $stmt = $this->db->prepare("INSERT INTO tallas (secuencia_id, talla, cantidad) VALUES (:secuencia_id, :talla, :cantidad)");
        $stmt->bindParam(':secuencia_id', $secuenciaId);
        $stmt->bindParam(':talla', $talla);
        $stmt->bindParam(':cantidad', $cantidad);
        return $stmt->execute();
    }
    
}
