<?php
require_once '../../controllers/pagos/pagosControllers.php';

if (isset($_POST['iddetop'])) {
    $controller = new PagosController();
    $iddetop = intval($_POST['iddetop']);
    $ordenes = $controller->gePrSByDOP($iddetop);
    header('Content-Type: application/json');
    echo json_encode($ordenes);
} else {
    header('Content-Type: application/json');
    echo json_encode([]);
}