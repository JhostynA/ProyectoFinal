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
            $talla_s = $_POST['talla_s'];
            $talla_m = $_POST['talla_m'];
            $talla_l = $_POST['talla_l'];
            $talla_xl = $_POST['talla_xl'];
    
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

        // Obtener las cantidades de tallas
        $talla_s = $_POST['talla_s'];
        $talla_m = $_POST['talla_m'];
        $talla_l = $_POST['talla_l'];
        $talla_xl = $_POST['talla_xl'];

        $prendasArealizar = $talla_s + $talla_m + $talla_l + $talla_xl;

        // Crear la secuencia en el modelo
        $sequenceCreated = $this->actionModel->createSequence($idop, $numSecuencia, $fechaInicio, $fechaFinal, $prendasArealizar, $talla_s, $talla_m, $talla_l, $talla_xl);

        if (!$sequenceCreated) {
            header("Location:../../views/produccion/indexP.php?action=view&id=$idop&error=NumSecuenciaDuplicado");
            exit();
        }

        // Obtener el ID de la última secuencia creada
        $lastSequenceId = $this->actionModel->getLastInsertedSequenceId();

        // Aquí ya no necesitas crear las tallas, ya que se están creando en el método createSequence del modelo.

        header("Location:../../views/produccion/indexP.php?action=view&id=$idop");
        exit();
    }
}

}
