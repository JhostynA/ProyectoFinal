<?php
require_once '../../models/Conexion.php';

$talla = isset($_GET['talla']) ? strtoupper($_GET['talla']) : '';
$secuenciaId = isset($_GET['secuencia_id']) ? intval($_GET['secuencia_id']) : 0;

$tallasValidas = ['S', 'M', 'L', 'XL'];
if (!in_array($talla, $tallasValidas) || $secuenciaId <= 0) {
    echo json_encode(["error" => "Talla no especificada o no válida, o secuencia_id no válido"]);
    exit;
}

try {
    $conexion = (new Conexion())->getConexion();
    
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

    $historial = $stmtHistorial->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($historial);
} catch (Exception $e) {
    echo json_encode(["error" => "Hubo un problema al obtener el historial: " . $e->getMessage()]);
}
?>
