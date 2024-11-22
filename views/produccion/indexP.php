<?php

require '../../models/produccion/ActionModel.php';
require '../../controllers/produccion/ActionController.php';

$actionModel = new ActionModel();
$actionController = new ActionController($actionModel);

$cliente_id = isset($_GET['cliente_id']) ? (int) $_GET['cliente_id'] : null;


if (isset($_GET['action'])) {
    if ($_GET['action'] === 'createOrdenProduccion') {
        $actionController->createOrdenProduccion();
    } elseif ($_GET['action'] === 'view' && isset($_GET['id'])) {
        $actionController->viewAction($_GET['id']);
    } elseif ($_GET['action'] === 'viewSecuencia' && isset($_GET['iddetop'])) { 
        $actionController->viewSecuencia($_GET['iddetop']);  
    } elseif ($_GET['action'] === 'viewPDF' && isset($_GET['id'])) { 
        $actionController->viewActionPDF($_GET['id']);
    } elseif ($_GET['action'] === 'createSequence') { 
        $actionController->createSequence(); 
    } elseif ($_GET['action'] === 'createClientAction') { 
        $actionController->createClientAction(); 
    }elseif ($_GET['action'] === 'updateClientAction') { 
        $actionController->updateClientAction();
    }elseif ($_GET['action'] === 'createProduccion') { 
        $actionController->createProduccion();
    }
} else {
    $actionController->showActions($cliente_id);
}
