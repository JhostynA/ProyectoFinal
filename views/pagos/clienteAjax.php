<?php
require_once '../../controllers/pagos/pagosControllers.php';

if (isset($_POST['idcliente'])) {
    $controller = new PagosController();
    $idcliente = intval($_POST['idcliente']);
    $ordenes = $controller->getOPByCliente($idcliente);
    header('Content-Type: application/json');
    echo json_encode($ordenes);
} else {
    header('Content-Type: application/json');
    echo json_encode([]);
}
