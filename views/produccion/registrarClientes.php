<?php
require_once '../../contenido.php';
require_once '../../models/produccion/ActionModel.php';

// Crear una instancia del modelo
$clienteModel = new ActionModel();
$clientes = $clienteModel->getClientes();
?>

<style>
    .inactivo {
        opacity: 0.5;  /* Reduce la opacidad de las filas inactivas */
        background-color: #f8d7da;  /* Opcional: cambiar el fondo de las filas inactivas */
    }
</style>


<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-4">Listado de Clientes</h2>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">Agregar Cliente</button>
            <a href="<?= $host ?>/views/produccion/registrarProduccion.php" class="btn btn-secondary">Regresar</a>
        </div>
    </div>

    <!-- Campo de búsqueda -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Buscar por nombre, teléfono o email" onkeyup="searchTable()">

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
ññ                <th class="text-center" style="width: 110px;">Teléfono</th>
                <th class="text-center" style="width: 200px;">Email</th>
                <th class="text-center" style="width: 150px;">Fecha Añadidoñ</th>
                <th class="text-center" style="width: 150px;">Estado</th>
                <th class="text-center" style="width: 100px;">Acciones</th>
            </tr>
        </thead>
        <tbody id="clientTable">
            <?php foreach ($clientes as $cliente): ?>
                <!-- Aplicar la clase "inactivo" si el cliente está inactivo -->
                <tr class="<?php echo is_null($cliente['inactive_at']) ? '' : 'inactivo'; ?>">
                    <td><?php echo !empty($cliente['nombrecliente']) ? htmlspecialchars($cliente['nombrecliente']) : 'Sin dato'; ?></td>
                    <td><?php echo !empty($cliente['telefono']) ? htmlspecialchars($cliente['telefono']) : 'Sin dato'; ?></td>
                    <td><?php echo !empty($cliente['email']) ? htmlspecialchars($cliente['email']) : 'Sin dato'; ?></td>
                    <td><?php echo !empty($cliente['fecha_creacion']) ? htmlspecialchars($cliente['fecha_creacion']) : 'Sin dato'; ?></td>
                    <td class="text-center">
                        <?php echo is_null($cliente['inactive_at']) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>'; ?>
                    </td>
                    <td class="text-center">
                        <!-- Botón para abrir el modal de actualización específico -->
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateClientModal<?php echo $cliente['id']; ?>">Actualizar</button>
                    </td>
                </tr>

                <!-- Modal para Actualizar Cliente -->
                <div class="modal fade" id="updateClientModal<?php echo $cliente['id']; ?>" tabindex="-1" aria-labelledby="updateClientModalLabel<?php echo $cliente['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="<?= $host ?>/views/produccion/indexP.php?action=updateClientAction&id=<?php echo $cliente['id']; ?>" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateClientModalLabel<?php echo $cliente['id']; ?>">Actualizar Cliente</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="nombrecliente<?php echo $cliente['id']; ?>" class="form-label">Nombre del Cliente</label>
                                        <input type="text" class="form-control" id="nombrecliente<?php echo $cliente['id']; ?>" name="nombrecliente" value="<?php echo htmlspecialchars($cliente['nombrecliente']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono<?php echo $cliente['id']; ?>" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono<?php echo $cliente['id']; ?>" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email<?php echo $cliente['id']; ?>" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email<?php echo $cliente['id']; ?>" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="estado<?php echo $cliente['id']; ?>" class="form-label">Estado</label>
                                        <select class="form-select" id="estado<?php echo $cliente['id']; ?>" name="estado">
                                            <option value="activo" <?php echo is_null($cliente['inactive_at']) ? 'selected' : ''; ?>>Activo</option>
                                            <option value="inactivo" <?php echo !is_null($cliente['inactive_at']) ? 'selected' : ''; ?>>Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal para Agregar Cliente -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= $host ?>/views/produccion/indexP.php?action=createClientAction" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Registrar Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombrecliente" class="form-label">Nombre del Cliente</label>
                        <input type="text" class="form-control" id="nombrecliente" name="nombrecliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../footer.php'; ?>

<script>
    // Función para filtrar las filas de la tabla
    function searchTable() {
        var input, filter, table, tr, td, i, j, txtValue, showRow;
        input = document.getElementById('searchInput');
        filter = input.value.toLowerCase();
        table = document.querySelector('.table');
        tr = table.getElementsByTagName('tr');
        
        // Iterar sobre cada fila de la tabla
        for (i = 1; i < tr.length; i++) {
            showRow = false;
            td = tr[i].getElementsByTagName('td');
            
            // Revisar cada celda de la fila
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        showRow = true;
                        break; // Si encuentra una coincidencia, se muestra la fila
                    }
                }
            }
            
            // Mostrar o esconder la fila
            if (showRow) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>

