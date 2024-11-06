<?php

require_once '../../models/Login.php';

class ActionModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    public function createAction($nombre, $fecha_inicio, $fecha_entrega, $talla_s, $talla_m, $talla_l, $talla_xl) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM actions WHERE nombre = :nombre");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO actions (nombre, fecha_inicio, fecha_entrega, talla_s, talla_m, talla_l, talla_xl) VALUES (:nombre, :fecha_inicio, :fecha_entrega, :talla_s, :talla_m, :talla_l, :talla_xl)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_entrega', $fecha_entrega);
        $stmt->bindParam(':talla_s', $talla_s);
        $stmt->bindParam(':talla_m', $talla_m);
        $stmt->bindParam(':talla_l', $talla_l);
        $stmt->bindParam(':talla_xl', $talla_xl);
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
    
        // Verifica si se recuperan datos
        $sequences = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$sequences) {
            error_log("No se encontraron secuencias para la OP con ID: $actionId");
        }
        return $sequences;
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
    
    public function createSequence($idop, $fechaInicio, $fechaFinal, $prendasArealizar, $talla_s, $talla_m, $talla_l, $talla_xl) {
        // Obtener el último número de secuencia existente
        $stmt = $this->db->prepare("SELECT MAX(numSecuencia) FROM secuencias WHERE idop = :idop");
        $stmt->bindParam(':idop', $idop);
        $stmt->execute();
        $lastNumSecuencia = $stmt->fetchColumn();
        $numSecuencia = $lastNumSecuencia ? $lastNumSecuencia + 1 : 1; // Si no hay secuencias, empieza desde 1
    
        // Verificar si el número de secuencia ya existe
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM secuencias WHERE idop = :idop AND numSecuencia = :numSecuencia");
        $stmt->bindParam(':idop', $idop);
        $stmt->bindParam(':numSecuencia', $numSecuencia);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            return false; 
        }
    
        $stmt = $this->db->prepare("SELECT talla_s, talla_m, talla_l, talla_xl FROM actions WHERE id = :idop");
        $stmt->bindParam(':idop', $idop);
        $stmt->execute();
        $action = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Compara las cantidades de las tallas
        if ($talla_s > $action['talla_s'] || $talla_m > $action['talla_m'] || $talla_l > $action['talla_l'] || $talla_xl > $action['talla_xl']) {
            return false;
        }
    
        $prendasFaltantes = $prendasArealizar;
    
        $stmt = $this->db->prepare("INSERT INTO secuencias (idop, numSecuencia, fechaInicio, fechaFinal, prendasArealizar, prendasFaltantes, talla_s, talla_m, talla_l, talla_xl) 
        VALUES (:idop, :numSecuencia, :fechaInicio, :fechaFinal, :prendasArealizar, :prendasFaltantes, :talla_s, :talla_m, :talla_l, :talla_xl)");
        $stmt->bindParam(':idop', $idop);
        $stmt->bindParam(':numSecuencia', $numSecuencia); 
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFinal', $fechaFinal);
        $stmt->bindParam(':prendasArealizar', $prendasArealizar);
        $stmt->bindParam(':prendasFaltantes', $prendasFaltantes);
        $stmt->bindParam(':talla_s', $talla_s);
        $stmt->bindParam(':talla_m', $talla_m);
        $stmt->bindParam(':talla_l', $talla_l);
        $stmt->bindParam(':talla_xl', $talla_xl);
    
        if ($stmt->execute()) {
            return $this->db->lastInsertId(); // Devuelve el ID de la secuencia creada
        }
    
        return false; // Si la inserción falla
    }
    
    

    public function getLastInsertedSequenceId() {
        return $this->db->lastInsertId();
    }
    
    public function createTalla($secuenciaId, $talla_s, $talla_m, $talla_l, $talla_xl, $cantidad, $realizadas_s = 0, $realizadas_m = 0, $realizadas_l = 0, $realizadas_xl = 0) {
        $stmt = $this->db->prepare("
            INSERT INTO tallas (secuencia_id, talla_s, talla_m, talla_l, talla_xl, cantidad, realizadas_s, realizadas_m, realizadas_l, realizadas_xl) 
            VALUES (:secuencia_id, :talla_s, :talla_m, :talla_l, :talla_xl, :cantidad, :realizadas_s, :realizadas_m, :realizadas_l, :realizadas_xl)
        ");
        
        $stmt->bindParam(':secuencia_id', $secuenciaId);
        $stmt->bindParam(':talla_s', $talla_s);
        $stmt->bindParam(':talla_m', $talla_m);
        $stmt->bindParam(':talla_l', $talla_l);
        $stmt->bindParam(':talla_xl', $talla_xl);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':realizadas_s', $realizadas_s);
        $stmt->bindParam(':realizadas_m', $realizadas_m);
        $stmt->bindParam(':realizadas_l', $realizadas_l);
        $stmt->bindParam(':realizadas_xl', $realizadas_xl);
        
        return $stmt->execute();
    }
    

    public function getTotalPrendasByActionId($actionId) {
        $query = "SELECT 
                    SUM(talla_s) AS talla_s, 
                    SUM(talla_m) AS talla_m, 
                    SUM(talla_l) AS talla_l, 
                    SUM(talla_xl) AS talla_xl 
                  FROM secuencias 
                  WHERE idop = :idop";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idop', $actionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function createKardexMovement($talla_id, $fecha, $cantidad, $talla) {
        try {
            // Insertar en la tabla kardex
            $stmt = $this->db->prepare("INSERT INTO kardex (talla_id, fecha, cantidad) VALUES (:talla_id, :fecha, :cantidad)");
            $stmt->bindParam(':talla_id', $talla_id);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':cantidad', $cantidad);
    
            // Ejecutar la consulta para insertar
            if ($stmt->execute()) {
                // Definir la columna a actualizar según la talla
                $columna = '';
                $realizadas_columna = '';
        
                switch ($talla) {
                    case 'S':
                        $realizadas_columna = 'realizadas_s';
                        break;
                    case 'M':
                        $realizadas_columna = 'realizadas_m';
                        break;
                    case 'L':
                        $realizadas_columna = 'realizadas_l';
                        break;
                    case 'XL':
                        $realizadas_columna = 'realizadas_xl';
                        break;
                }
    
                // Actualizar la cantidad en la columna específica de la talla en 'tallas'
                $stmt = $this->db->prepare("UPDATE tallas SET $realizadas_columna = $realizadas_columna + :cantidad WHERE id = :talla_id");

                // Usar un marcador de parámetro para la columna de talla
                $stmt->bindParam(':cantidad', $cantidad);
                $stmt->bindParam(':talla_id', $talla_id);
    
                // Ejecutar la actualización
                if ($stmt->execute()) {
                    return true;
                } else {
                    error_log('Error al ejecutar la actualización en tallas');
                    return false;
                }
            } else {
                error_log('Error al ejecutar la inserción en kardex');
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error en la base de datos: " . $e->getMessage());
            return false;
        }
    }
     
}

