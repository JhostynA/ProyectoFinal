<?php
require_once '../../models/Conexion.php';

// Obtenemos la talla y el ID de la secuencia del parámetro GET
$talla = isset($_GET['talla']) ? strtoupper($_GET['talla']) : '';
$secuenciaId = isset($_GET['secuencia_id']) ? intval($_GET['secuencia_id']) : 0;

// Validamos que la talla sea válida y el secuencia_id sea mayor a 0
$tallasValidas = ['S', 'M', 'L', 'XL'];
if (!in_array($talla, $tallasValidas) || $secuenciaId <= 0) {
    echo json_encode(["error" => "Talla no especificada o no válida, o secuencia_id no válido"]);
    exit;
}

try {
    $conexion = (new Conexion())->getConexion();
    
    // Modificamos la consulta para filtrar por secuencia y por la nueva columna de talla en kardex
    $queryHistorial = "
        SELECT kardex.fecha, kardex.cantidad, kardex.talla
        FROM kardex
        JOIN tallas ON tallas.id = kardex.talla_id
        WHERE tallas.secuencia_id = :secuencia_id 
          AND kardex.talla = :talla;
    ";

    $stmtHistorial = $conexion->prepare($queryHistorial);
    $stmtHistorial->bindParam(':secuencia_id', $secuenciaId, PDO::PARAM_INT);
    $stmtHistorial->bindParam(':talla', $talla, PDO::PARAM_STR);
    $stmtHistorial->execute();

    // Obtenemos los resultados del historial
    $historial = $stmtHistorial->fetchAll(PDO::FETCH_ASSOC);

    // Retornamos los datos como un JSON
    echo json_encode($historial);
} catch (Exception $e) {
    // Si hay algún error en la conexión o consulta
    echo json_encode(["error" => "Hubo un problema al obtener el historial: " . $e->getMessage()]);
}
?>
