<?php

require_once '/xampp/htdocs/LinoFino/models/Conexion.php';

class Pago extends Conexion
{

    private $pdo;

    // Constructor que recibe la conexión de la base de datos
    public function __construct()
    {
        $this->pdo = parent::getConexion();
    }

    public function registrarPago($params = []): array
    {
        try {
            $tsql = "CALL spu_registrar_pago (?,?,?,?,?)";
            $query = $this->pdo->prepare($tsql);
            $query->execute(array(
                $params['_idpersona'],
                $params['_idoperacion'],
                $params['_idop'],
                $params['_idsecuencia'],
                $params['_prendas_realizadas']

            ));
            $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    /*  public function registrarPago($nombre_persona, $nombre_operacion, $nombre_op, $numSecuencia, $prendas_realizadas)
    {
        try {
            // Obtener el ID de la persona por su nombre completo (sin importar mayúsculas/minúsculas)
            $stmt = $this->db->prepare("SELECT idpersona FROM personas WHERE LOWER(CONCAT(nombres, ' ', apepaterno, ' ', apematerno)) = LOWER(:nombre_persona)");
            $stmt->bindParam(":nombre_persona", $nombre_persona);
            $stmt->execute();
            $idpersona = $stmt->fetchColumn();
            if (!$idpersona) {
                throw new Exception('Persona no encontrada');
            }

            // Obtener el precio de la operación por su nombre (sin importar mayúsculas/minúsculas)
            $stmt = $this->db->prepare("SELECT idoperacion, precio FROM operaciones WHERE LOWER(operacion) = LOWER(:nombre_operacion)");
            $stmt->bindParam(":nombre_operacion", $nombre_operacion);
            $stmt->execute();
            $operacion = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$operacion) {
                throw new Exception('Operación no encontrada');
            }
            $idoperacion = $operacion['idoperacion'];
            $precio = $operacion['precio'];

            // Obtener el ID de la orden de producción por su nombre (sin importar mayúsculas/minúsculas)
            $stmt = $this->db->prepare("SELECT id FROM actions WHERE LOWER(nombre) = LOWER(:nombre_op)");
            $stmt->bindParam(":nombre_op", $nombre_op);
            $stmt->execute();
            $idop = $stmt->fetchColumn();
            if (!$idop) {
                throw new Exception('Orden de producción no encontrada');
            }

            // Verificar que la secuencia pertenece a la orden de producción (sin importar mayúsculas/minúsculas)
            $stmt = $this->db->prepare("SELECT id FROM secuencias WHERE LOWER(numSecuencia) = LOWER(:numSecuencia) AND idop = :idop");
            $stmt->bindParam(":numSecuencia", $numSecuencia);
            $stmt->bindParam(":idop", $idop);
            $stmt->execute();
            $idsecuencia = $stmt->fetchColumn();
            if (!$idsecuencia) {
                throw new Exception('La secuencia no pertenece a la orden de producción seleccionada');
            }

            // Calcular el total del pago
            $total_pago = $prendas_realizadas * $precio;

            // Insertar el pago
            $stmt = $this->db->prepare("INSERT INTO pagos (idpersona, idoperacion, idop, idsecuencia, prendas_realizadas, precio, total_pago) 
                                        VALUES (:idpersona, :idoperacion, :idop, :idsecuencia, :prendas_realizadas, :precio, :total_pago)");
            $stmt->bindParam(":idpersona", $idpersona);
            $stmt->bindParam(":idoperacion", $idoperacion);
            $stmt->bindParam(":idop", $idop);
            $stmt->bindParam(":idsecuencia", $idsecuencia);
            $stmt->bindParam(":prendas_realizadas", $prendas_realizadas);
            $stmt->bindParam(":precio", $precio);
            $stmt->bindParam(":total_pago", $total_pago);
            $stmt->execute();

            // Obtener el pago insertado
            $pagoId = $this->db->lastInsertId();

            // Devolver la información relevante del pago
            return [
                'idpago' => $pagoId,
                'nombre_persona' => $nombre_persona,
                'nombre_operacion' => $nombre_operacion,
                'nombre_secuencia' => $numSecuencia,
                'prendas_realizadas' => $prendas_realizadas,
                'precio' => $precio,
                'total_pago' => $total_pago
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    } */


    /* // Método para registrar un pago
    public function registrarPago($idpersona, $idoperacion, $idop, $idsecuencia, $prendas_realizadas)
    {
        try {
            // Verificar existencia de la persona
            $stmt = $this->db->prepare("SELECT CONCAT(nombres, ' ', apepaterno, ' ', apematerno) FROM personas WHERE idpersona = :idpersona");
            $stmt->bindParam(":idpersona", $idpersona);
            $stmt->execute();
            $persona = $stmt->fetchColumn();
            if (!$persona) {
                throw new Exception('Persona no encontrada');
            }

            // Verificar existencia de la operación y obtener su precio
            $stmt = $this->db->prepare("SELECT precio FROM operaciones WHERE idoperacion = :idoperacion");
            $stmt->bindParam(":idoperacion", $idoperacion);
            $stmt->execute();
            $precio = $stmt->fetchColumn();
            if (!$precio) {
                throw new Exception('Operación no encontrada');
            }

            // Verificar existencia de la orden de producción
            $stmt = $this->db->prepare("SELECT 1 FROM actions WHERE id = :idop");
            $stmt->bindParam(":idop", $idop);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('Orden de producción no encontrada');
            }

            // Verificar que la secuencia pertenece a la orden de producción
            $stmt = $this->db->prepare("SELECT COUNT(*), numSecuencia FROM secuencias WHERE id = :idsecuencia AND idop = :idop");
            $stmt->bindParam(":idsecuencia", $idsecuencia);
            $stmt->bindParam(":idop", $idop);
            $stmt->execute();
            $existeSecuencia = $stmt->fetchColumn();
            if ($existeSecuencia == 0) {
                throw new Exception('La secuencia no pertenece a la orden de producción seleccionada');
            }

            // Calcular el total del pago
            $total_pago = $prendas_realizadas * $precio;

            // Insertar el pago
            $stmt = $this->db->prepare("INSERT INTO pagos (idpersona, idoperacion, idop, idsecuencia, prendas_realizadas, precio, total_pago) 
                                        VALUES (:idpersona, :idoperacion, :idop, :idsecuencia, :prendas_realizadas, :precio, :total_pago)");
            $stmt->bindParam(":idpersona", $idpersona);
            $stmt->bindParam(":idoperacion", $idoperacion);
            $stmt->bindParam(":idop", $idop);
            $stmt->bindParam(":idsecuencia", $idsecuencia);
            $stmt->bindParam(":prendas_realizadas", $prendas_realizadas);
            $stmt->bindParam(":precio", $precio);
            $stmt->bindParam(":total_pago", $total_pago);
            $stmt->execute();

            // Obtener el pago insertado
            $pagoId = $this->db->lastInsertId();

            // Devolver la información relevante del pago
            return [
                'idpago' => $pagoId,
                'nombre_persona' => $persona,
                'nombre_operacion' => $this->getOperacionNombre($idoperacion),
                'nombre_secuencia' => $this->getSecuenciaNombre($idsecuencia),
                'prendas_realizadas' => $prendas_realizadas,
                'precio' => $precio,
                'total_pago' => $total_pago
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    } */

    // Método para obtener el nombre de la operación
    private function getOperacionNombre($idoperacion)
    {
        $stmt = $this->pdo->prepare("SELECT operacion FROM operaciones WHERE idoperacion = :idoperacion");
        $stmt->bindParam(":idoperacion", $idoperacion);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Método para obtener el nombre de la secuencia
    private function getSecuenciaNombre($idsecuencia)
    {
        $stmt = $this->pdo->prepare("SELECT numSecuencia FROM secuencias WHERE id = :idsecuencia");
        $stmt->bindParam(":idsecuencia", $idsecuencia);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Método para listar todos los pagos
    public function listarPagos()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    P.idpago,
                    CONCAT(PE.nombres, ' ', PE.apepaterno, ' ', PE.apematerno) AS nombre_persona,
                    O.operacion AS nombre_operacion,
                    A.nombre AS nombre_op,
                    S.numSecuencia AS nombre_secuencia,
                    P.prendas_realizadas,
                    P.total_pago, 
                    P.create_at
                FROM 
                    pagos P
                JOIN personas PE ON P.idpersona = PE.idpersona
                JOIN operaciones O ON P.idoperacion = O.idoperacion
                JOIN actions A ON P.idop = A.id
                JOIN secuencias S ON P.idsecuencia = S.id");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Método para actualizar un pago
    public function actualizarPago($idpago, $idpersona, $idoperacion, $idop, $idsecuencia, $prendas_realizadas)
    {
        try {
            // Verificar existencia de la persona
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM personas WHERE idpersona = :idpersona");
            $stmt->bindParam(":idpersona", $idpersona);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                throw new Exception('Persona no encontrada');
            }

            // Verificar existencia de la operación
            $stmt = $this->pdo->prepare("SELECT precio FROM operaciones WHERE idoperacion = :idoperacion");
            $stmt->bindParam(":idoperacion", $idoperacion);
            $stmt->execute();
            $precio = $stmt->fetchColumn();
            if (!$precio) {
                throw new Exception('Operación no encontrada');
            }

            // Verificar existencia de la orden de producción
            $stmt = $this->pdo->prepare("SELECT 1 FROM actions WHERE id = :idop");
            $stmt->bindParam(":idop", $idop);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('Orden de producción no encontrada');
            }

            // Verificar que la secuencia pertenece a la orden de producción
            $stmt = $this->pdo->prepare("SELECT 1 FROM secuencias WHERE id = :idsecuencia AND idop = :idop");
            $stmt->bindParam(":idsecuencia", $idsecuencia);
            $stmt->bindParam(":idop", $idop);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('La secuencia no pertenece a la orden de producción seleccionada');
            }

            // Calcular el total del pago
            $total_pago = $prendas_realizadas * $precio;

            // Actualizar el pago
            $stmt = $this->pdo->prepare("UPDATE pagos 
                                        SET 
                                            idpersona = :idpersona,
                                            idoperacion = :idoperacion,
                                            idop = :idop,
                                            idsecuencia = :idsecuencia,
                                            prendas_realizadas = :prendas_realizadas,
                                            precio = :precio,
                                            total_pago = :total_pago
                                        WHERE idpago = :idpago");
            $stmt->bindParam(":idpago", $idpago);
            $stmt->bindParam(":idpersona", $idpersona);
            $stmt->bindParam(":idoperacion", $idoperacion);
            $stmt->bindParam(":idop", $idop);
            $stmt->bindParam(":idsecuencia", $idsecuencia);
            $stmt->bindParam(":prendas_realizadas", $prendas_realizadas);
            $stmt->bindParam(":precio", $precio);
            $stmt->bindParam(":total_pago", $total_pago);
            $stmt->execute();

            return $this->getPagoById($idpago);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Método para obtener un pago por ID
    private function getPagoById($idpago)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                P.idpago,
                CONCAT(PE.nombres, ' ', PE.apepaterno, ' ', PE.apematerno) AS nombre_persona,
                O.operacion AS nombre_operacion,
                A.nombre AS nombre_op,
                S.numSecuencia AS nombre_secuencia,
                P.prendas_realizadas,
                P.total_pago, 
                P.create_at
            FROM pagos P
            JOIN personas PE ON P.idpersona = PE.idpersona
            JOIN operaciones O ON P.idoperacion = O.idoperacion
            JOIN actions A ON P.idop = A.id
            JOIN secuencias S ON P.idsecuencia = S.id
            WHERE P.idpago = :idpago");
        $stmt->bindParam(":idpago", $idpago);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para eliminar un pago
    public function eliminarPago($idpago)
    {
        try {
            // Verificar existencia del pago
            $stmt = $this->pdo->prepare("SELECT 1 FROM pagos WHERE idpago = :idpago");
            $stmt->bindParam(":idpago", $idpago);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                throw new Exception('Pago no encontrado');
            }

            // Obtener los datos del pago antes de eliminarlo
            $stmt = $this->pdo->prepare("SELECT 
                                            CONCAT(PE.nombres, ' ', PE.apepaterno, ' ', PE.apematerno) AS nombre_persona,
                                            O.operacion AS nombre_operacion,
                                            S.numSecuencia AS nombre_secuencia,
                                            P.prendas_realizadas,
                                            P.total_pago
                                        FROM pagos P
                                        JOIN personas PE ON P.idpersona = PE.idpersona
                                        JOIN operaciones O ON P.idoperacion = O.idoperacion
                                        JOIN secuencias S ON P.idsecuencia = S.id
                                        WHERE P.idpago = :idpago");
            $stmt->bindParam(":idpago", $idpago);
            $stmt->execute();
            $pagoData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Eliminar el pago
            $stmt = $this->pdo->prepare("DELETE FROM pagos WHERE idpago = :idpago");
            $stmt->bindParam(":idpago", $idpago);
            $stmt->execute();

            return array_merge($pagoData, ['mensaje' => 'Pago eliminado correctamente']);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
 


/* $pago = new Pago(); */

/* echo json_encode($pago->registrarPago(4, 2, 1, 1, 3000));
 */

/* echo json_encode($pago->listarPagos());
 */

/* echo json_encode($pago->actualizarPago(2, 2, 6, 1, 1, 9000));
 */

/* echo json_encode($pago->eliminarPago(2)); */