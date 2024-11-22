<?php

define('BASE_PATH', dirname(__DIR__)); 
require_once(BASE_PATH . '/Login.php');


class ActionModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

   


    public function createClient($razonsocial, $nombrecomercial, $telefono, $email, $direccion, $contacto) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM clientes WHERE razonsocial = :razonsocial");
        $stmt->bindParam(':razonsocial', $razonsocial);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            return false;
        }
    
        $stmt = $this->db->prepare("
            INSERT INTO clientes (razonsocial, nombrecomercial, telefono, email, direccion, contacto) 
            VALUES (:razonsocial, :nombrecomercial, :telefono, :email, :direccion, :contacto)
        ");
        $stmt->bindParam(':razonsocial', $razonsocial);
        $stmt->bindParam(':nombrecomercial', $nombrecomercial);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':contacto', $contacto);
    
        return $stmt->execute();
    }
    
    
    public function updateClient($idcliente, $razonsocial, $nombrecomercial, $telefono, $email, $direccion, $contacto, $inactive_at) {
        $sql = "UPDATE clientes 
                SET razonsocial = :razonsocial,
                    nombrecomercial = :nombrecomercial,
                    telefono = :telefono,
                    email = :email,
                    direccion = :direccion,
                    contacto = :contacto,
                    inactive_at = :inactive_at
                WHERE idcliente = :idcliente";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':idcliente', $idcliente, PDO::PARAM_INT);
        $stmt->bindParam(':razonsocial', $razonsocial, PDO::PARAM_STR);
        $stmt->bindParam(':nombrecomercial', $nombrecomercial, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $stmt->bindParam(':contacto', $contacto, PDO::PARAM_STR);
        $stmt->bindParam(':inactive_at', $inactive_at, PDO::PARAM_STR); 
    
        return $stmt->execute();
    }
    
    public function getClientesActivos() {
        $stmt = $this->db->prepare("SELECT idcliente, nombrecomercial FROM clientes WHERE inactive_at IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientes(){
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY inactive_at IS NULL DESC, fecha_creacion DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createOrdenProduccion($idcliente, $op, $estilo, $division, $color, $fechainicio, $fechafin) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM ordenesproduccion WHERE op = :op");
        $stmt->bindParam(':op', $op);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            return false;
        }
    
        $stmt = $this->db->prepare("
            INSERT INTO ordenesproduccion (idcliente, op, estilo, division, color, fechainicio, fechafin) 
            VALUES (:idcliente, :op, :estilo, :division, :color, :fechainicio, :fechafin)
        ");
    
        $stmt->bindParam(':idcliente', $idcliente);
        $stmt->bindParam(':op', $op);
        $stmt->bindParam(':estilo', $estilo);
        $stmt->bindParam(':division', $division);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':fechainicio', $fechainicio);
        $stmt->bindParam(':fechafin', $fechafin);
    
        return $stmt->execute();
    }
    
    public function getTallas() {
        $query = "SELECT idtalla, talla FROM tallas";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPersonasActivas() {
        $query = "SELECT idpersona, nombres, apepaterno, apematerno FROM personas WHERE fechabaja IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOperaciones() {
        $query = "SELECT idoperacion, operacion FROM operaciones";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getActions() {
        $stmt = $this->db->query("SELECT * FROM ordenesproduccion ORDER BY created_at DESC;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActionsByClient($cliente_id) {
        $stmt = $this->db->prepare("SELECT * FROM ordenesproduccion WHERE idcliente = ? ORDER BY created_at DESC");
        $stmt->execute([$cliente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActionById($id) {
        $stmt = $this->db->prepare("SELECT * FROM actions WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetalleByOP($idop) {
        $query = "
            SELECT 
                detalleop.iddetop,
                detalleop.numSecuencia,
                detalleop.cantidad,
                detalleop.sinicio,
                detalleop.sfin,
                tallas.talla
            FROM 
                detalleop
            INNER JOIN 
                tallas
            ON 
                detalleop.idtalla = tallas.idtalla
            WHERE 
                detalleop.idop = :idop
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idop', $idop);
        $stmt->execute();
    
        $sequences = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$sequences) {
            error_log("No se encontraron detalles para la OP con ID: $idop");
        }
        return $sequences;
    }

    public function getProduccionByDOP($iddetop) {
        $query = "
            SELECT 
                produccion.idproduccion,
                produccion.cantidadproducida,
                produccion.fecha,
                produccion.pagado,
                produccion.fechapagopersona,
                personas.nombres AS nombrePersona,
                personas.apepaterno AS apellidoPaterno,
                personas.apematerno AS apellidoMaterno,
                operaciones.operacion AS tipoOperacion
            FROM 
                produccion
            INNER JOIN 
                personas
            ON 
                produccion.idpersona = personas.idpersona
            INNER JOIN 
                operaciones
            ON 
                produccion.idtipooperacion = operaciones.idoperacion
            WHERE 
                produccion.iddetop = :iddetop
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':iddetop', $iddetop, PDO::PARAM_INT);
        $stmt->execute();
    
        $producciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$producciones) {
            error_log("No se encontraron producciones para la OP con ID: $iddetop");
        }
        return $producciones;
    }
    
    
    public function getSecuenciaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM detalleop WHERE iddetop = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function insertDetalleOp($idop, $idtalla, $numSecuencia, $cantidad, $fechaInicio, $fechaFinal) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM detalleop WHERE idop = :idop AND numSecuencia = :numSecuencia AND idtalla = :idtalla");
        $stmt->bindParam(':idop', $idop);
        $stmt->bindParam(':numSecuencia', $numSecuencia);
        $stmt->bindParam(':idtalla', $idtalla);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            return false; 
        }
    
        $stmt = $this->db->prepare("
            INSERT INTO detalleop (idop, idtalla, numSecuencia, cantidad, sinicio, sfin) 
            VALUES (:idop, :idtalla, :numSecuencia, :cantidad, :sinicio, :sfin)
        ");
        $stmt->bindParam(':idop', $idop);
        $stmt->bindParam(':idtalla', $idtalla);
        $stmt->bindParam(':numSecuencia', $numSecuencia);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':sinicio', $fechaInicio);
        $stmt->bindParam(':sfin', $fechaFinal);
    
        if ($stmt->execute()) {
            return $this->db->lastInsertId(); 
        }
    
        return false; 
    }

    public function createProduccion($iddetop, $idpersona, $idtipooperacion, $cantidadproducida, $fecha) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM produccion 
            WHERE iddetop = :iddetop 
              AND idpersona = :idpersona 
              AND idtipooperacion = :idtipooperacion 
              AND DATE(fecha) = DATE(:fecha)
        ");
        $stmt->bindParam(':iddetop', $iddetop);
        $stmt->bindParam(':idpersona', $idpersona);
        $stmt->bindParam(':idtipooperacion', $idtipooperacion);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            return false; 
        }
    
        $stmt = $this->db->prepare("
            INSERT INTO produccion (iddetop, idpersona, idtipooperacion, cantidadproducida, fecha) 
            VALUES (:iddetop, :idpersona, :idtipooperacion, :cantidadproducida, :fecha)
        ");
        $stmt->bindParam(':iddetop', $iddetop);
        $stmt->bindParam(':idpersona', $idpersona);
        $stmt->bindParam(':idtipooperacion', $idtipooperacion);
        $stmt->bindParam(':cantidadproducida', $cantidadproducida);
        $stmt->bindParam(':fecha', $fecha);
    
        if ($stmt->execute()) {
            return $this->db->lastInsertId(); 
        }
    
        return false; 
    }

    public function getLastInsertedSequenceId() {
        return $this->db->lastInsertId();
    }

    public function getActionByIdxPDF($id) {
        $stmt = $this->db->prepare("SELECT * FROM ordenesproduccion WHERE idop = :idop");
        $stmt->bindParam(':idop', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPDFByActionId($actionId) {
        $query = "SELECT * FROM detalleop WHERE iddetop = :iddetop";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':iddetop', $iddetop);
        $stmt->execute();
    
        $sequences = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$sequences) {
            error_log("No se encontraron secuencias para la OP con ID: $iddetop");
        }
        return $sequences;
    }

    public function actualizarInventario($actionId) {
        $stmt = $this->db->prepare("CALL actualizar_inventario(:actionId)");
        $stmt->bindParam(':actionId', $actionId, PDO::PARAM_INT);
        return $stmt->execute();
    }    
    
     
}

