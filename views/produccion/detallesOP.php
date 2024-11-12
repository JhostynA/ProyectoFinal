<?php 
require_once '../../contenido.php';
require_once '../../models/Conexion.php'; 

$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

$clienteId = $_GET['idcliente'] ?? null;
echo "<p>ID del Cliente recibido: " . htmlspecialchars($clienteId) . "</p>";


?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Detalles de Producción del Cliente</h1>
    
    <?php 
    if ($clienteId) {
        // Agrega un mensaje para verificar que el cliente ID se está pasando correctamente
        echo "<p>ID del Cliente: " . htmlspecialchars($clienteId) . "</p>";

        // Prepara y ejecuta la consulta
        $stmt = $conexion->prepare("SELECT * FROM actions WHERE idcliente = :idcliente");
        $stmt->bindParam(':idcliente', $clienteId, PDO::PARAM_INT);
        $stmt->execute();
        $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verifica si se encontraron resultados
        if ($actions && count($actions) > 0): ?>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estilo</th>
                        <th>División</th>
                        <th>OP</th>
                        <th>Color</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Entrega</th>
                        <th>Cantidad Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actions as $action): ?>
                        <tr>
                            <td><?= htmlspecialchars($action['id']) ?></td>
                            <td><?= htmlspecialchars($action['estilo']) ?></td>
                            <td><?= htmlspecialchars($action['division']) ?></td>
                            <td><?= htmlspecialchars($action['nombre']) ?></td>
                            <td><?= htmlspecialchars($action['color']) ?></td>
                            <td><?= htmlspecialchars($action['fecha_inicio']) ?></td>
                            <td><?= htmlspecialchars($action['fecha_entrega']) ?></td>
                            <td><?= htmlspecialchars($action['cantidad_prendas']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron detalles de producción para este cliente.</p>
        <?php endif;
    } else {
        echo "<p>Cliente no especificado.</p>";
    }
    ?>
</div>

<?php require_once '../../footer.php'; ?>
