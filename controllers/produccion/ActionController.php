<?php

class ActionController {
    private $actionModel;

    public function __construct($actionModel) {
        $this->actionModel = $actionModel;
    }
    
    public function createClientAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombrecliente'])) {
            $nombrecliente = $_POST['nombrecliente'];
            $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
    
            // Intentar crear el cliente a través del modelo
            if ($this->actionModel->createClient($nombrecliente, $telefono, $email)) {
                header('Location: ../../views/produccion/registrarClientes.php');
                exit();
            } else {
                header('Location: ../../views/produccion/registrarClientes.php?error=ClienteYaExiste');
                exit();
            }
        }
    }

    public function updateClientAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $nombrecliente = $_POST['nombrecliente'];
            $telefono = $_POST['telefono'];
            $email = $_POST['email'];
            $estado = $_POST['estado'];
    
            // Actualiza el campo inactive_at basado en el estado seleccionado
            $inactive_at = ($estado === 'inactivo') ? date('Y-m-d H:i:s') : null;
    
            if ($this->actionModel->updateClient($id, $nombrecliente, $telefono, $email, $inactive_at)) {
                header('Location: ../../views/produccion/registrarClientes.php');
                exit();
            } else {
                header('Location: ../../views/produccion/registrarClientes.php?error=ErrorActualizacion');
                exit();
            }
        }
    }
    
    

    public function createAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
            $idcliente = $_POST['idcliente'];
            $estilo = $_POST['estilo'];
            $division = $_POST['division'];
            $nombre = $_POST['nombre'];
            $color = $_POST['color'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_entrega = $_POST['fecha_entrega'];
            
            $talla_s = isset($_POST['talla_s']) ? $_POST['talla_s'] : 0;
            $talla_m = isset($_POST['talla_m']) ? $_POST['talla_m'] : 0;
            $talla_l = isset($_POST['talla_l']) ? $_POST['talla_l'] : 0;
            $talla_xl = isset($_POST['talla_xl']) ? $_POST['talla_xl'] : 0;
    
            if ($this->actionModel->createAction($idcliente, $estilo, $division, $nombre, $color, $fecha_inicio, $fecha_entrega, $talla_s, $talla_m, $talla_l, $talla_xl)) {
                header('Location: ../../views/produccion/registrarProduccion.php');
                exit();
            } else {
                header('Location: ../../views/produccion/registrarProduccion.php?error=NombreYaExiste');
                exit();
            }
        }
    }
    
    
    public function showActions($cliente_id = null) {
        if ($cliente_id) {
            $actions = $this->actionModel->getActionsByClient($cliente_id);
        } else {
            $actions = $this->actionModel->getActions();
        }
        require '../../views/produccion/actions.php';
    }
    

    public function viewAction($id) {
        $action = $this->actionModel->getActionById($id);
        $secuencias = $this->actionModel->getSecuenciasByActionId($id);
        require '../../views/produccion/view.Action.php';
    }
    
    public function viewSecuencia($id) {
        $secuencia = $this->actionModel->getSecuenciaById($id);
        $tallas = $this->actionModel->getTallasBySecuenciaId($id); 
        require '../../views/produccion/viewSecuencia.php'; 
    }    

    public function viewActionPDF($id) {
        $actionP = $this->actionModel->getActionByIdxPDF($id);
    
        $pdfs = $this->actionModel->getPDFByActionId($id);
    
        require '../../views/produccion/archivosPDF.php';
    }
    
    

    public function createSequence() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idcliente = $_POST['idop'];  // El cliente id es lo que necesitas
            $fechaInicio = $_POST['fechaInicio'];
            $fechaFinal = $_POST['fechaFinal'];
    
            $numSecuencia = isset($_POST['numSecuencia']) ? (int)$_POST['numSecuencia'] : 1;
    
            $talla_s = isset($_POST['talla_s']) ? (int)$_POST['talla_s'] : 0;
            $talla_m = isset($_POST['talla_m']) ? (int)$_POST['talla_m'] : 0;
            $talla_l = isset($_POST['talla_l']) ? (int)$_POST['talla_l'] : 0;
            $talla_xl = isset($_POST['talla_xl']) ? (int)$_POST['talla_xl'] : 0;
    
            $prendasArealizar = $talla_s + $talla_m + $talla_l + $talla_xl;
    
            $sequenceCreated = $this->actionModel->createSequence($idcliente, $numSecuencia, $fechaInicio, $fechaFinal, $prendasArealizar, $talla_s, $talla_m, $talla_l, $talla_xl);
    
            if (!$sequenceCreated) {
                header("Location:../../views/produccion/indexP.php?cliente_id=" . urlencode($idcliente) . "&error=SecuenciaNoCreada");
                exit();
            }
    
            $lastSequenceId = $this->actionModel->getLastInsertedSequenceId();
            $this->actionModel->createTalla($lastSequenceId, $talla_s, $talla_m, $talla_l, $talla_xl, $prendasArealizar, 0, 0, 0, 0);
    
            // Redirige a la vista de producción para el cliente
            header("Location:../../views/produccion/indexP.php?cliente_id=" . urlencode($idcliente));
            exit();
        }
    }
    

}
