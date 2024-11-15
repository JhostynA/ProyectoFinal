<?php

require_once '/xampp/htdocs/LinoFino/models/pagos/Secuencias.php';

header('Content-Type: application/json');  // Asegura que la respuesta sea JSON

$secuencia = new Secuencia(); // Crear una instancia del modelo Secuencia

if (isset($_GET['operation'])) {

    $operation = $_GET['operation'];

    // Operación para listar secuencias por ID de OP
    if ($operation === 'listarSecuenciasPorOp') {
        if (isset($_GET['opId'])) {
            $opId = $_GET['opId'];
            $resultado = $secuencia->listarSecuenciasPorOp($opId); // Llamar al método listarSecuenciasPorOp
            echo json_encode($resultado);
        } else {
            echo json_encode(['error' => 'No se proporcionó el ID de la operación para listar las secuencias.']);
        }
    }

    // Operación para buscar secuencias por nombre de OP
    elseif ($operation === 'BuscarSecuencia') {
        if (isset($_GET['opNombre'])) {
            $opNombre = $_GET['opNombre'];
            $resultado = $secuencia->buscarSecuenciasPorOp($opNombre); // Llamar al método buscarSecuenciasPorOp
            echo json_encode($resultado);
        } else {
            echo json_encode(['error' => 'No se proporcionó el nombre de la operación para buscar las secuencias.']);
        }
    }

    // En caso de operación desconocida
    else {
        echo json_encode(['error' => 'Operación no válida.']);
    }
} else {
    echo json_encode(['error' => 'No se especificó ninguna operación.']);
}
