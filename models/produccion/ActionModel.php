<?php

define('BASE_PATH', dirname(__DIR__)); // Define el directorio raíz del proyecto
require_once(BASE_PATH . '/Login.php');


class ActionModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

   


    public function createClient($nombrecliente, $telefono, $email) {
        // Verificar si el cliente ya existe
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM clientes WHERE nombrecliente = :nombrecliente");
        $stmt->bindParam(':nombrecliente', $nombrecliente);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            return false;
        }
    
        // Insertar el nuevo cliente
        $stmt = $this->db->prepare("INSERT INTO clientes (nombrecliente, telefono, email) VALUES (:nombrecliente, :telefono, :email)");
        $stmt->bindParam(':nombrecliente', $nombrecliente);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
    
    public function updateClient($id, $nombrecliente, $telefono, $email, $inactive_at) {
        // Construimos la consulta de actualización
        $sql = "UPDATE clientes 
                SET nombrecliente = :nombrecliente,
                    telefono = :telefono,
                    email = :email,
                    inactive_at = :inactive_at
                WHERE id = :id";
        
        // Preparamos la consulta
        $stmt = $this->db->prepare($sql);
        
        // Asignamos los valores a los parámetros
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombrecliente', $nombrecliente, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':inactive_at', $inactive_at, PDO::PARAM_STR);

        // Ejecutamos la consulta y retornamos el resultado
        return $stmt->execute();
    }

    public function createAction($idcliente, $estilo, $division, $nombre, $color, $fecha_inicio, $fecha_entrega, $talla_s, $talla_m, $talla_l, $talla_xl) {
        // Verificar si el nombre ya existe
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM actions WHERE nombre = :nombre");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            return false;
        }
    
        // Calcular la cantidad total de prendas
        $cantidad_prendas = (int)$talla_s + (int)$talla_m + (int)$talla_l + (int)$talla_xl;
    
        // Preparar la consulta de inserción con los nuevos campos
        $stmt = $this->db->prepare("INSERT INTO actions (idcliente, estilo, division, nombre, color, fecha_inicio, fecha_entrega, talla_s, talla_m, talla_l, talla_xl, cantidad_prendas) VALUES (:idcliente, :estilo, :division, :nombre, :color, :fecha_inicio, :fecha_entrega, :talla_s, :talla_m, :talla_l, :talla_xl, :cantidad_prendas)");
    
        // Bind de los parámetros
        $stmt->bindParam(':idcliente', $idcliente);
        $stmt->bindParam(':estilo', $estilo);
        $stmt->bindParam(':division', $division);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_entrega', $fecha_entrega);
        $stmt->bindParam(':talla_s', $talla_s);
        $stmt->bindParam(':talla_m', $talla_m);
        $stmt->bindParam(':talla_l', $talla_l);
        $stmt->bindParam(':talla_xl', $talla_xl);
        $stmt->bindParam(':cantidad_prendas', $cantidad_prendas);
        return $stmt->execute();
    }

    public function getClientesActivos() {
        $stmt = $this->db->prepare("SELECT id, nombrecliente FROM clientes WHERE inactive_at IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getClientes(){
        // Ordenar primero los clientes activos (inactive_at IS NULL) y luego los inactivos
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY inactive_at IS NULL DESC, fecha_creacion DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    public function getActions() {
        $stmt = $this->db->query("SELECT * FROM actions ORDER BY created_at DESC;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActionsByClient($cliente_id) {
        $stmt = $this->db->prepare("SELECT * FROM actions WHERE idcliente = ? ORDER BY created_at DESC");
        $stmt->execute([$cliente_id]);
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
    
    public function createSequence($idop, $numSecuencia, $fechaInicio, $fechaFinal, $prendasArealizar, $talla_s, $talla_m, $talla_l, $talla_xl) {
    // Verifica que no exista un duplicado con el mismo idop y numSecuencia
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM secuencias WHERE idop = :idop AND numSecuencia = :numSecuencia");
    $stmt->bindParam(':idop', $idop);
    $stmt->bindParam(':numSecuencia', $numSecuencia);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        return false; // Ya existe una secuencia con este número
    }

    // Verifica que las cantidades de tallas no excedan las permitidas en 'actions'
    $stmt = $this->db->prepare("SELECT talla_s, talla_m, talla_l, talla_xl FROM actions WHERE id = :idop");
    $stmt->bindParam(':idop', $idop);
    $stmt->execute();
    $action = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($talla_s > $action['talla_s'] || $talla_m > $action['talla_m'] || $talla_l > $action['talla_l'] || $talla_xl > $action['talla_xl']) {
        return false;
    }

    $prendasFaltantes = $prendasArealizar;

    // Inserta la nueva secuencia
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
        return $this->db->lastInsertId(); 
    }

    return false; 
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
            $stmt = $this->db->prepare("INSERT INTO kardex (talla_id, fecha, cantidad, talla) VALUES (:talla_id, :fecha, :cantidad, :talla)");
            $stmt->bindParam(':talla_id', $talla_id, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':talla', $talla, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                
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
    
                $stmt = $this->db->prepare("UPDATE tallas SET $realizadas_columna = $realizadas_columna + :cantidad WHERE id = :talla_id");
                $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmt->bindParam(':talla_id', $talla_id, PDO::PARAM_INT);
    
                if ($stmt->execute()) {
                    $stmt = $this->db->prepare("UPDATE secuencias 
                                                SET prendasFaltantes = prendasFaltantes - :cantidad
                                                WHERE id = (SELECT secuencia_id FROM tallas WHERE id = :talla_id)");
                    $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
                    $stmt->bindParam(':talla_id', $talla_id, PDO::PARAM_INT);
    
                    if ($stmt->execute()) {
                        return true; 
                    } else {
                        error_log('Error al ejecutar la actualización en secuencias');
                        return false;
                    }
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
    

    public function getActionByIdxPDF($id) {
        $stmt = $this->db->prepare("SELECT * FROM actions WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPDFByActionId($actionId) {
        $query = "SELECT * FROM secuencias WHERE id = :actionId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':actionId', $actionId);
        $stmt->execute();
    
        $sequences = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$sequences) {
            error_log("No se encontraron secuencias para la OP con ID: $actionId");
        }
        return $sequences;
    }

    public function actualizarInventario($actionId) {
        $stmt = $this->db->prepare("CALL actualizar_inventario(:actionId)");
        $stmt->bindParam(':actionId', $actionId, PDO::PARAM_INT);
        return $stmt->execute();
    }    
    
     
}

