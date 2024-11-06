<?php
require_once '../../models/produccion/ActionModel.php'; // Incluye el modelo

$model = new ActionModel(); // Instancia el modelo

// Obtener los datos del cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si los datos llegaron correctamente
if (isset($data['talla_id'], $data['fecha'], $data['cantidad'], $data['talla'])) {
    $talla_id = $data['talla_id'];
    $fecha = $data['fecha'];
    $cantidad = $data['cantidad'];
    $talla = $data['talla'];

    // Verificar si los datos llegaron correctamente
    error_log("Talla ID: $talla_id, Fecha: $fecha, Cantidad: $cantidad, Talla: $talla");

    // Llamar al método del modelo para registrar el movimiento
    if ($model->createKardexMovement($talla_id, $fecha, $cantidad, $talla)) {
        echo 'Movimiento registrado con éxito.';
    } else {
        echo 'Error al registrar el movimiento.';
    }
} else {
    echo 'Faltan datos en la solicitud.';
}
?>
