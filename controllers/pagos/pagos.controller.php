<?php

require_once '../../models/pagos/Pagos.php';

header('Content-Type: application/json');
$pagos = new Pago();

// Verificamos si existe una operación que debe ejecutarse
if (isset($_GET['operation'])) {

    $pago = new Pago(); // Creamos una instancia del modelo Pago

    // Operación para listar todos los pagos (usamos GET)
    if ($_GET['operation'] == 'listarPagos') {
        $resultado = $pago->listarPagos(); // Llamamos al método listarPagos
        echo json_encode($resultado); // Enviamos la lista de pagos como respuesta en JSON
    }
}

if (isset($_POST['operation'])) {
    switch ($_POST['operation']) {
        case 'register':
            $datos = [
                '_idpersona' => $_POST['_idpersona'],
                '_idoperacion' => $_POST['_idoperacion'],
                '_idop' => $_POST['_idop'],
                '_idsecuencia' => $_POST['_idsecuencia'],
                '_prendas_realizadas' => $_POST['_prendas_realizadas']
            ];
            $resultado = $pagos->registrarPago($datos);
            echo json_encode($resultado);
            break;
    }



    /*     $pago = new Pago(); // Creamos una instancia del modelo Pago

    // Operación para registrar un nuevo pago (usamos POST)
    if ($_POST['operation'] == 'registrarPago') {
        if (isset($_POST['nombre_persona'], $_POST['nombre_operacion'], $_POST['nombre_op'], $_POST['numSecuencia'], $_POST['prendas_realizadas'])) {
            $nombre_persona = $_POST['nombre_persona'];
            $nombre_operacion = $_POST['nombre_operacion'];
            $nombre_op = $_POST['nombre_op'];
            $numSecuencia = $_POST['numSecuencia'];
            $prendas_realizadas = $_POST['prendas_realizadas'];

            // Llamamos al método registrarPago y obtenemos el resultado
            $resultado = $pago->registrarPago($nombre_persona, $nombre_operacion, $nombre_op, $numSecuencia, $prendas_realizadas);
            echo json_encode($resultado); // Enviamos el resultado como respuesta en JSON
        } else {
            echo json_encode(['error' => 'Faltan parámetros para registrar el pago.']);
        }
    } */

    // Operación para actualizar un pago (usamos POST)
    if ($_POST['operation'] == 'actualizarPago') {
        if (isset($_POST['idpago'], $_POST['idpersona'], $_POST['idoperacion'], $_POST['idop'], $_POST['idsecuencia'], $_POST['prendas_realizadas'])) {
            $idpago = $_POST['idpago'];
            $idpersona = $_POST['idpersona'];
            $idoperacion = $_POST['idoperacion'];
            $idop = $_POST['idop'];
            $idsecuencia = $_POST['idsecuencia'];
            $prendas_realizadas = $_POST['prendas_realizadas'];

            // Llamamos al método actualizarPago y obtenemos el resultado
            $resultado = $pago->actualizarPago($idpago, $idpersona, $idoperacion, $idop, $idsecuencia, $prendas_realizadas);
            echo json_encode($resultado); // Enviamos el resultado como respuesta en JSON
        } else {
            echo json_encode(['error' => 'Faltan parámetros para actualizar el pago.']);
        }
    }

    // Operación para eliminar un pago (usamos POST)
    if ($_POST['operation'] == 'eliminarPago') {
        if (isset($_POST['idpago'])) {
            $idpago = $_POST['idpago'];

            // Llamamos al método eliminarPago y obtenemos el resultado
            $resultado = $pago->eliminarPago($idpago);
            echo json_encode($resultado); // Enviamos el resultado como respuesta en JSON
        } else {
            echo json_encode(['error' => 'Faltan parámetros para eliminar el pago.']);
        }
    }
}
