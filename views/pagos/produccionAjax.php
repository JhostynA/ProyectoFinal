<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../models/Conexion.php';
require_once '../../controllers/pagos/pagosControllers.php';

header('Content-Type: application/json');

try {
    if (isset($_POST['idpersona'])) {
        $conexionObj = new Conexion();
        $conn = $conexionObj->getConexion();

        $controller = new PagosController($conn);
        $idpersona = intval($_POST['idpersona']);

        // Registro para depuración
       

        $registros = $controller->getProduccionByPersona($idpersona);

        // Registro de salida
        

        echo json_encode($registros);
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    // Registro de excepciones
  
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

?>
