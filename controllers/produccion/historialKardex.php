<?php
require_once '../../contenido.php';

$talla = isset($_GET['talla']) ? $_GET['talla'] : '';

$conexion = (new Conexion())->getConexion();

// Consulta para obtener el historial de la talla
$queryHistorial = "SELECT * FROM kardex WHERE talla_id = (SELECT id FROM tallas WHERE talla = ?)";

$stmtHistorial = $conexion->prepare($queryHistorial);
$stmtHistorial->execute([$talla]);

// Obtener los resultados del historial
$historial = $stmtHistorial->fetchAll(PDO::FETCH_ASSOC);

// Retornar los datos como un JSON
echo json_encode($historial);
?>
