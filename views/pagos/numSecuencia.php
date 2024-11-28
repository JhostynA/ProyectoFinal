<?php
require_once '../../controllers/pagos/pagosControllers.php';

if (isset($_POST['idop'])) {
    $controller = new PagosController();
    $idop = intval($_POST['idop']);
    $ordenes = $controller->getNSByOP($idop);
    header('Content-Type: application/json');
    echo json_encode($ordenes);
} else {
    header('Content-Type: application/json');
    echo json_encode([]);
}