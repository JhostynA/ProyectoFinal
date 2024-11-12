<?php

require '../../models/produccion/ActionModel.php';
require '../../controllers/produccion/ActionController.php';

$actionModel = new ActionModel();
$actionController = new ActionController($actionModel);

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<div class="alert alert-success" role="alert">Secuencia creada exitosamente.</div>';
}

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'create') {
        $actionController->createAction();
    } elseif ($_GET['action'] === 'view' && isset($_GET['id'])) {
        $actionController->viewAction($_GET['id']);
    } elseif ($_GET['action'] === 'viewSecuencia' && isset($_GET['id'])) { 
        $actionController->viewSecuencia($_GET['id']);  
    } elseif ($_GET['action'] === 'viewPDF' && isset($_GET['id'])) { 
        $actionController->viewActionPDF($_GET['id']);
    } elseif ($_GET['action'] === 'createSequence') { 
        $actionController->createSequence(); 
    } elseif ($_GET['action'] === 'createClientAction') { 
        $actionController->createClientAction(); 
    }elseif ($_GET['action'] === 'updateClientAction' && isset($_GET['id'])) { 
        $actionController->updateClientAction();
    }
} else {
    $actionController->showActions();
}
