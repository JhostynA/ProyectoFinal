<?php
require_once '../../models/produccion/ActionModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $iddetop = $data['iddetop'];

    $secuenciasModel = new ActionModel();
    $operacionesSeleccionadas = $secuenciasModel->getOperacionesSeleccionadas($iddetop);

    echo json_encode(['operacionesSeleccionadas' => $operacionesSeleccionadas]);
    exit;
}
?>
