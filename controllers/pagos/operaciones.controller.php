<?php

require_once '/xampp/htdocs/LinoFino/models/pagos/Operaciones.php';

header('Content-Type: application/json');  // Asegura que la respuesta sea JSON

if (isset($_GET['operation'])) {

    $operacion = new Operacion(); // Crear una instancia del modelo Operacion
    
    // Operación para listar operaciones
    if ($_GET['operation'] == 'listar') {
        if (isset($_GET['query'])) {
            $query = $_GET['query']; // Capturar el término de búsqueda desde la consulta GET
            $resultado = $operacion->listarOperaciones($query); // Llamar al método listarOperaciones en el modelo
            echo json_encode($resultado); // Enviar la respuesta como JSON
        } else {
            echo json_encode(['error' => 'No se proporcionó una consulta para listar.']); // Si no se proporciona query
        }
    }
}

if (isset($_GET['operation'])) {

    $operacion = new Operacion(); // Crear una nueva instancia del modelo Operacion

    // Operación para buscar operaciones
    if ($_GET['operation'] == 'BuscarOperacion') {
        if (isset($_GET['query'])) {
            $query = $_GET['query']; // Capturar el término de búsqueda
            $resultado = $operacion->buscarOperaciones($query); // Llamar al método buscarOperaciones en el modelo
            echo json_encode($resultado); // Devolver la respuesta como JSON
        } else {
            echo json_encode(['error' => 'No se proporcionó una consulta para buscar.']); // Error si no se proporciona query
        }
    }
}

