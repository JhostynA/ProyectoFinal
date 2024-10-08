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
        require '../../views/produccion/actions.php'; // Carga la vista con las acciones
    }

    public function viewAction($id) {
        $action = $this->actionModel->getActionById($id);
        require '../../views/produccion/view.Action.php'; // Carga la vista para una acción específica
    }

    
}
