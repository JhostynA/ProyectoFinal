<?php
require_once '../../contenido.php';
require_once '../../models/produccion/ActionModel.php';

$clienteModel = new ActionModel();
$clientes = $clienteModel->getClientes();
?>

<style>
    .inactivo {
        opacity: 0.5;  
        background-color: #f8d7da;  
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

    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Buscar por nombre, teléfono o email" onkeyup="searchTable()">

    

<div class="table-responsive">
<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th class="text-center" style="width: 150px;">Razón Social</th>
            <th class="text-center" style="width: 150px;">Nombre Comercial</th>
            <th class="text-center" style="width: 110px;">Teléfono</th>
            <th class="text-center" style="width: 200px;">Email</th>
            <th class="text-center" style="width: 250px;">Dirección</th>
            <th class="text-center" style="width: 150px;">Persona de Contacto</th>
            <th class="text-center" style="width: 150px;">Fecha Creación</th>
            <th class="text-center" style="width: 150px;">Estado</th>
            <th class="text-center" style="width: 100px;">Acciones</th>
        </tr>
    </thead>
    <tbody id="clientTable">
        <?php foreach ($clientes as $cliente): ?>
            <tr class="<?php echo is_null($cliente['inactive_at']) ? '' : 'inactivo'; ?>">
                <td><?php echo !empty($cliente['razonsocial']) ? htmlspecialchars($cliente['razonsocial']) : 'Sin dato'; ?></td>
                <td><?php echo !empty($cliente['nombrecomercial']) ? htmlspecialchars($cliente['nombrecomercial']) : 'Sin dato'; ?></td>
                <td><?php echo !empty($cliente['telefono']) ? htmlspecialchars($cliente['telefono']) : 'Sin dato'; ?></td>
                <td><?php echo !empty($cliente['email']) ? htmlspecialchars($cliente['email']) : 'Sin dato'; ?></td>
                <td><?php echo !empty($cliente['direccion']) ? htmlspecialchars($cliente['direccion']) : 'Sin dato'; ?></td>
                <td><?php echo !empty($cliente['contacto']) ? htmlspecialchars($cliente['contacto']) : 'Sin dato'; ?></td>
                <td><?php echo !empty($cliente['fecha_creacion']) ? htmlspecialchars($cliente['fecha_creacion']) : 'Sin dato'; ?></td>
                <td class="text-center">
                    <?php echo is_null($cliente['inactive_at']) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>'; ?>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateClientModal<?php echo $cliente['idcliente']; ?>">Actualizar</button>
                </td>
            </tr>
                <!-- Modal para Actualizar Cliente -->
                <div class="modal fade" id="updateClientModal<?php echo $cliente['idcliente']; ?>" tabindex="-1" aria-labelledby="updateClientModalLabel<?php echo $cliente['idcliente']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <form action="<?= $host ?>/views/produccion/indexP.php?action=updateClientAction" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateClientModalLabel<?php echo $cliente['idcliente']; ?>">Actualizar Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <input type="hidden" name="idcliente" value="<?php echo $cliente['idcliente']; ?>">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="razonsocial<?php echo $cliente['idcliente']; ?>" class="form-label">Razón Social</label>
                                    <input type="text" class="form-control" id="razonsocial<?php echo $cliente['idcliente']; ?>" name="razonsocial" value="<?php echo htmlspecialchars($cliente['razonsocial']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nombrecomercial<?php echo $cliente['idcliente']; ?>" class="form-label">Nombre Comercial</label>
                                    <input type="text" class="form-control" id="nombrecomercial<?php echo $cliente['idcliente']; ?>" name="nombrecomercial" value="<?php echo htmlspecialchars($cliente['nombrecomercial']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="telefono<?php echo $cliente['idcliente']; ?>" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono<?php echo $cliente['idcliente']; ?>" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email<?php echo $cliente['idcliente']; ?>" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email<?php echo $cliente['idcliente']; ?>" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="direccion<?php echo $cliente['idcliente']; ?>" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion<?php echo $cliente['idcliente']; ?>" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="contacto<?php echo $cliente['idcliente']; ?>" class="form-label">Persona de Contacto</label>
                                    <input type="text" class="form-control" id="contacto<?php echo $cliente['idcliente']; ?>" name="contacto" value="<?php echo htmlspecialchars($cliente['contacto']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="estado<?php echo $cliente['idcliente']; ?>" class="form-label">Estado</label>
                                    <select class="form-select" id="estado<?php echo $cliente['idcliente']; ?>" name="estado">
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
                        <label for="razonsocial" class="form-label">Razón Social</label>
                        <input type="text" class="form-control" id="razonsocial" name="razonsocial">
                    </div>
                    <div class="mb-3">
                        <label for="nombrecomercial" class="form-label">Nombre Comercial</label>
                        <input type="text" class="form-control" id="nombrecomercial" name="nombrecomercial">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="number" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                    <div class="mb-3">
                        <label for="contacto" class="form-label">Contacto</label>
                        <input type="text" class="form-control" id="contacto" name="contacto">
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
    function searchTable() {
        var input, filter, table, tr, td, i, j, txtValue, showRow;
        input = document.getElementById('searchInput');
        filter = input.value.toLowerCase();
        table = document.querySelector('.table');
        tr = table.getElementsByTagName('tr');
        
        for (i = 1; i < tr.length; i++) {
            showRow = false;
            td = tr[i].getElementsByTagName('td');
            
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        showRow = true;
                        break; 
                    }
                }
            }
            
            if (showRow) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>

