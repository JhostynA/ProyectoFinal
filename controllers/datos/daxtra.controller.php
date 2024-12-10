<?php
require_once '../../models/Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];
    $conexion = (new Conexion())->getConexion();

    // Preparar la llamada al procedimiento almacenado
    $stmt = $conexion->prepare("CALL listarPagosPorBusqueda(:searchTerm)");
    $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();

    // Obtener los resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar los resultados en formato JSON
    echo json_encode($resultados);
}
?>
