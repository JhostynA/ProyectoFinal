<?php

require_once '/xampp/htdocs/LinoFino/models/pagos/Ops.php';

header('Content-Type: application/json');  // Asegura que la respuesta sea JSON

if (isset($_GET['operation'])) {

    $op = new Op(); // Crear una instancia del modelo Op (Orden de Producción)
    
    // Operación para listar órdenes de producción (Ops)
    if ($_GET['operation'] == 'listar') {
        if (isset($_GET['query'])) {
            $query = $_GET['query']; // Capturar el término de búsqueda desde la consulta GET
            $resultado = $op->listarOps($query); // Llamar al método listarOps en el modelo
            echo json_encode($resultado); // Enviar la respuesta como JSON
        } else {
            echo json_encode(['error' => 'No se proporcionó una consulta para listar.']); // Si no se proporciona query
        }
    }
}

if (isset($_GET['operation'])) {

    $op = new Op(); // Crear una nueva instancia del modelo Op

    // Operación para buscar órdenes de producción (Ops)
    if ($_GET['operation'] == 'BuscarOp') {
        if (isset($_GET['query'])) {
            $query = $_GET['query']; // Capturar el término de búsqueda
            $resultado = $op->buscarOps($query); // Llamar al método buscarOps en el modelo
            echo json_encode($resultado); // Devolver la respuesta como JSON
        } else {
            echo json_encode(['error' => 'No se proporcionó una consulta para buscar.']); // Error si no se proporciona query
        }
    }
}

