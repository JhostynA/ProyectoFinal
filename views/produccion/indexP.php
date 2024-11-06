<?php

require '../../models/produccion/ActionModel.php';
require '../../controllers/produccion/ActionController.php';

$actionModel = new ActionModel();
$actionController = new ActionController($actionModel);

// Verificar si hay un mensaje de éxito
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<div class="alert alert-success" role="alert">Secuencia creada exitosamente.</div>';
}

// Verificar la acción a realizar
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'create') {
        $actionController->createAction();
    } elseif ($_GET['action'] === 'view' && isset($_GET['id'])) {
        $actionController->viewAction($_GET['id']);
    } elseif ($_GET['action'] === 'viewSecuencia' && isset($_GET['id'])) { 
        $actionController->viewSecuencia($_GET['id']);  
    } elseif ($_GET['action'] === 'viewPDF' && isset($_GET['id'])) { // Nueva acción para ver PDF
        $actionController->viewActionPDF($_GET['id']);
    } elseif ($_GET['action'] === 'createSequence') { 
        $actionController->createSequence(); 
    }
} else {
    $actionController->showActions();
}
