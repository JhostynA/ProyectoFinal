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
            
            // Obtener las cantidades de tallas
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

    public function createSequence() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idop = $_POST['idop'];
            $numSecuencia = $_POST['numSecuencia'];
            $fechaInicio = $_POST['fechaInicio'];
            $fechaFinal = $_POST['fechaFinal'];
    
            // Obtener las cantidades de tallas, asegurando que se manejen las tallas no ingresadas
            $talla_s = isset($_POST['talla_s']) ? (int)$_POST['talla_s'] : 0;
            $talla_m = isset($_POST['talla_m']) ? (int)$_POST['talla_m'] : 0;
            $talla_l = isset($_POST['talla_l']) ? (int)$_POST['talla_l'] : 0;
            $talla_xl = isset($_POST['talla_xl']) ? (int)$_POST['talla_xl'] : 0;
    
            $prendasArealizar = $talla_s + $talla_m + $talla_l + $talla_xl;
    
            // Crear la secuencia en el modelo
            $sequenceCreated = $this->actionModel->createSequence($idop, $numSecuencia, $fechaInicio, $fechaFinal, $prendasArealizar, $talla_s, $talla_m, $talla_l, $talla_xl);
    
            if (!$sequenceCreated) {
                header("Location:../../views/produccion/indexP.php?action=view&id=$idop&error=NumSecuenciaDuplicado");
                exit();
            }
    
            // Obtener el ID de la última secuencia creada
            $lastSequenceId = $this->actionModel->getLastInsertedSequenceId();
    
            // Insertar tallas en la tabla tallas solo si el valor es mayor a 0
            $realizadas = 0; // O el valor que necesites para 'realizadas'
    
            // Insertar tallas solo si tienen valores
            $this->actionModel->createTalla($lastSequenceId, $talla_s, $talla_m, $talla_l, $talla_xl, $prendasArealizar, $realizadas);
    
            header("Location:../../views/produccion/indexP.php?action=view&id=$idop");
            exit();
        }
    }
    
    
    
}
