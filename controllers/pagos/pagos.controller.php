<?php

require_once '/xampp/htdocs/LinoFino/models/pagos/Pagos.php';

$pagos = new Pagos();

// Manejo de solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si se ha enviado una operación
    if (isset($_POST['operacion'])) {
        switch ($_POST['operacion']) {
            case 'register':
                // Verifica que se han proporcionado todos los datos necesarios
                if (isset($_POST['idpersona'], $_POST['idoperacion'], $_POST['prendas_realizadas'])) {
                    $idpersona = $_POST['idpersona'];
                    $idoperacion = $_POST['idoperacion'];
                    $prendas_realizadas = $_POST['prendas_realizadas'];

                    var_dump($idpersona, $idoperacion, $prendas_realizadas);
                    
                    // Llama al método para registrar el pago
                    $resultado = $pagos->registrarPago($idpersona, $idoperacion, $prendas_realizadas);

                    // Manejo de posibles errores en el registro
                    if (isset($resultado['error'])) {
                        echo json_encode(["estado" => "error", "mensaje" => $resultado['error']]);
                    } else {
                        echo json_encode(["estado" => "success", "idpago" => $resultado]);
                    }
                } else {
                    echo json_encode(["error" => "Faltan datos para registrar el pago"]);
                }
                break;
        }
    } else {
        echo json_encode(["error" => "No se especificó ninguna operación"]);
    }
}

// Manejo de solicitudes GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica si se ha enviado una operación
    if (isset($_GET['operacion'])) {
        switch ($_GET['operacion']) {
            case 'getAll':
                echo json_encode($pagos->obtenerPagos());
                break;
            case 'buscarPersonas':
                if (isset($_GET['term'])) {
                    $term = $_GET['term'];
                    echo json_encode($pagos->buscarPersonas($term));
                } else {
                    echo json_encode(["error" => "No se proporcionó un término de búsqueda"]);
                }
                break;
            case 'buscarOperaciones': // Agrega esta parte para buscar operaciones
                if (isset($_GET['termino'])) {
                    $termino = $_GET['termino'];
                    echo json_encode($pagos->buscarOperaciones($termino));
                } else {
                    echo json_encode(["error" => "No se proporcionó un término de búsqueda"]);
                }
                break;
            default:
                echo json_encode(["error" => "Operación no válida"]);
                break;
        }
    } else {
        echo json_encode(["error" => "No se especificó ninguna operación"]);
    }
}


