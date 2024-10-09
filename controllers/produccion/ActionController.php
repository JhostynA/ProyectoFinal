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
            $cantidad_prendas = $_POST['cantidad_prendas'];
            
            $this->actionModel->createAction($nombre, $fecha_inicio, $fecha_entrega, $cantidad_prendas);
            header('Location: ../../views/produccion/indexP.php');
            exit();
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
        $tallas = $this->actionModel->getTallasBySecuenciaId($id); // Obtener tallas asociadas a la secuencia
        require '../../views/produccion/viewSecuencia.php'; // Carga la vista para una secuencia específica
    }    

    
}
