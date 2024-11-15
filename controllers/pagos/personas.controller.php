<?php

require_once 'C:/xampp/htdocs/LinoFino/models/pagos/Personas.php';

header('Content-Type: application/json');  // Asegura que la respuesta sea JSON

if (isset($_GET['operation'])) {
    $persona = new Persona();

    // Operación para listar personas
    if ($_GET['operation'] == 'listar') {
        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $resultado = $persona->listar($query);
            // Verifica que la consulta devuelva resultados y responde
            if ($resultado) {
                echo json_encode($resultado);
            } else {
                echo json_encode(['error' => 'No se encontraron personas.']);
            }
        } else {
            echo json_encode(['error' => 'No se proporcionó una consulta para listar.']);
        }
    }

    // Operación para buscar personas
    if ($_GET['operation'] == 'BuscarPersona') {
        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $resultado = $persona->buscar($query);
            // Verifica que la consulta devuelva resultados y responde
            if ($resultado) {
                echo json_encode($resultado);
            } else {
                echo json_encode(['error' => 'No se encontraron resultados para la búsqueda.']);
            }
        } else {
            echo json_encode(['error' => 'No se proporcionó una consulta para buscar.']);
        }
    }
} else {
    echo json_encode(['error' => 'No se especificó la operación.']);
}

?>
