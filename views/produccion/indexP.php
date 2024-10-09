<?php

require '../../models/produccion/ActionModel.php';
require '../../controllers/produccion/ActionController.php';

$actionModel = new ActionModel();
$actionController = new ActionController($actionModel);

// Mostrar mensaje de éxito si se ha creado una secuencia
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
    } elseif ($_GET['action'] === 'createSequence') { // Agrega esta línea
        $actionController->createSequence(); // Llama al método para crear la secuencia
    }
} else {
    $actionController->showActions();
}
