<?php
require_once '../../models/Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fechaInicio']) && isset($_GET['fechaFin'])) {
    $fechaInicio = $_GET['fechaInicio'];
    $fechaFin = $_GET['fechaFin'];
    $conexion = (new Conexion())->getConexion();

    try {
        // Preparar la llamada al procedimiento almacenado
        $stmt = $conexion->prepare("CALL listarPagosPorFecha(:fechaInicio, :fechaFin)");
        $stmt->bindValue(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
        $stmt->bindValue(':fechaFin', $fechaFin, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retornar los resultados en formato JSON
        echo json_encode($resultados);
    } catch (Exception $e) {
        // Manejo de errores
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Respuesta si no se envían los parámetros requeridos
    echo json_encode(['error' => 'Parámetros fechaInicio y fechaFin requeridos']);
}
?>
