<?php

require '../../models/produccion/ActionModel.php';
require '../../controllers/produccion/ActionController.php';

$actionModel = new ActionModel();
$actionController = new ActionController($actionModel);

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'create') {
        $actionController->createAction();
    } elseif ($_GET['action'] === 'view' && isset($_GET['id'])) {
        $actionController->viewAction($_GET['id']);
    } elseif ($_GET['action'] === 'viewSecuencia' && isset($_GET['id'])) { 
        $actionController->viewSecuencia($_GET['id']);  
    }
} else {
    $actionController->showActions();
}
