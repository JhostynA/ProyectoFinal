<?php
require_once '../../models/produccion/ActionModel.php'; 

$model = new ActionModel(); 

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['talla_id'], $data['fecha'], $data['cantidad'], $data['talla'])) {
    $talla_id = $data['talla_id'];
    $fecha = $data['fecha'];
    $cantidad = $data['cantidad'];
    $talla = $data['talla'];

    error_log("Talla ID: $talla_id, Fecha: $fecha, Cantidad: $cantidad, Talla: $talla");

    if ($model->createKardexMovement($talla_id, $fecha, $cantidad, $talla)) {
        echo 'Movimiento registrado con éxito.';
    } else {
        echo 'Error al registrar el movimiento.';
    }
} else {
    echo 'Faltan datos en la solicitud.';
}
?>
