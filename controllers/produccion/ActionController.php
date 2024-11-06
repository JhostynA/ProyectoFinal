<?php

class ActionController {
    private $actionModel;

    public function __construct($actionModel) {
        $this->actionModel = $actionModel;
    }

    public function createAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
            $nombre = $_POST['nombre'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_entrega = $_POST['fecha_entrega'];
            
            $talla_s = isset($_POST['talla_s']) ? $_POST['talla_s'] : 0;
            $talla_m = isset($_POST['talla_m']) ? $_POST['talla_m'] : 0;
            $talla_l = isset($_POST['talla_l']) ? $_POST['talla_l'] : 0;
            $talla_xl = isset($_POST['talla_xl']) ? $_POST['talla_xl'] : 0;
    
            if ($this->actionModel->createAction($nombre, $fecha_inicio, $fecha_entrega, $talla_s, $talla_m, $talla_l, $talla_xl)) {
                header('Location: ../../views/produccion/indexP.php?success=1');
                exit();
            } else {
                header('Location: ../../views/produccion/indexP.php?error=NombreYaExiste');
                exit();
            }
        }
    }
    
    public function showActions() {
        $actions = $this->actionModel->getActions();
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
            $idop = $_POST['idop'];
            $fechaInicio = $_POST['fechaInicio'];
            $fechaFinal = $_POST['fechaFinal'];
    
            $talla_s = isset($_POST['talla_s']) ? (int)$_POST['talla_s'] : 0;
            $talla_m = isset($_POST['talla_m']) ? (int)$_POST['talla_m'] : 0;
            $talla_l = isset($_POST['talla_l']) ? (int)$_POST['talla_l'] : 0;
            $talla_xl = isset($_POST['talla_xl']) ? (int)$_POST['talla_xl'] : 0;
    
            $prendasArealizar = $talla_s + $talla_m + $talla_l + $talla_xl;
    
            $sequenceCreated = $this->actionModel->createSequence($idop, $fechaInicio, $fechaFinal, $prendasArealizar, $talla_s, $talla_m, $talla_l, $talla_xl);
    
            if (!$sequenceCreated) {
                header("Location:../../views/produccion/indexP.php?error=SecuenciaNoCreada");
                exit();
            }
    
            $lastSequenceId = $this->actionModel->getLastInsertedSequenceId();
    
            $this->actionModel->createTalla($lastSequenceId, $talla_s, $talla_m, $talla_l, $talla_xl, $prendasArealizar, 0, 0, 0, 0);
    
            header("Location:../../views/produccion/indexP.php");
            exit();
        }
    }  
}
