<?php require_once '../../contenido.php'; ?>

<div class="container mt-5">

    <h1 class="mb-4 text-center">PRODUCCIÓN</h1>

    <div class="d-flex justify-content-between mb-3 align-items-center">
        <div class="input-group search-container" style="max-width: 200px;">
            <input type="number" id="searchInput" class="form-control border-primary" placeholder="Buscar..." aria-label="Search">
        </div>

        <button type="button" class="btn btn-success shadow" data-toggle="modal" data-target="#createActionModal">
            Nueva Producción
        </button>
    </div>

    <table id="actionsTable" class="table table-hover table-bordered shadow-lg">
        <thead class="thead-dark">
            <tr>
                <th>OP</th>
                <th>Fecha Inicio</th>
                <th>Fecha Entrega</th>
                <th>Talla S</th>
                <th>Talla M</th>
                <th>Talla L</th>
                <th>Talla XL</th>
                <th>Total Prendas</th>
                <th>Progreso</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($actions as $action): ?>
                <tr>
                    <td>
                        <a href="<?= $host ?>/views/produccion/indexP.php?action=view&id=<?= $action['id'] ?>" class="text-success">
                            <?= htmlspecialchars($action['nombre']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($action['fecha_inicio']) ?></td>
                    <td><?= htmlspecialchars($action['fecha_entrega']) ?></td>
                    <td><?= htmlspecialchars($action['talla_s']) ?></td>
                    <td><?= htmlspecialchars($action['talla_m']) ?></td>
                    <td><?= htmlspecialchars($action['talla_l']) ?></td>
                    <td><?= htmlspecialchars($action['talla_xl']) ?></td>
                    <td><?= htmlspecialchars($action['talla_s'] + $action['talla_m'] + $action['talla_l'] + $action['talla_xl']) ?></td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated
                                <?php if ($action['porcentaje'] <= 40) echo 'bg-danger'; ?>
                                <?php if ($action['porcentaje'] > 40 && $action['porcentaje'] <= 80) echo 'bg-warning'; ?>
                                <?php if ($action['porcentaje'] > 80) echo 'bg-success'; ?>" 
                                role="progressbar" 
                                style="width: <?= $action['porcentaje'] ?>%;" 
                                aria-valuenow="<?= $action['porcentaje'] ?>" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                <?= $action['porcentaje'] ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Modal para crear nueva producción -->
<div class="modal fade" id="createActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Producción</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCreateAction" method="POST" action="<?= $host ?>/views/produccion/indexP.php?action=create">
                    <div class="form-group">
                        <label for="name">OP:</label>
                        <input type="number" class="form-control" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio:</label>
                        <input type="date" class="form-control" name="fecha_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_entrega">Fecha de Entrega:</label>
                        <input type="date" class="form-control" name="fecha_entrega" required>
                    </div>
                    <div class="form-group">
                        <label for="talla_s">Cantidad Talla S:</label>
                        <input type="number" class="form-control" name="talla_s" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="talla_m">Cantidad Talla M:</label>
                        <input type="number" class="form-control" name="talla_m" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="talla_l">Cantidad Talla L:</label>
                        <input type="number" class="form-control" name="talla_l" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="talla_xl">Cantidad Talla XL:</label>
                        <input type="number" class="form-control" name="talla_xl" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('error') && urlParams.get('error') === 'NombreYaExiste') {
            alert('Ya existe una OP con ese número');

            //Con esto limpiamos la URL, eliminando los parametros
            const newUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
    });

    $('#createActionModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset(); // Limpiar los campos del formulario
    });

    document.getElementById('searchInput').addEventListener('keyup', function() {
        var input = document.getElementById('searchInput').value.toLowerCase();
        var rows = document.getElementById('actionsTable').getElementsByTagName('tr');

        for (var i = 1; i < rows.length; i++) {
            var nombre = rows[i].getElementsByTagName('td')[0];
            if (nombre) {
                var txtValue = nombre.textContent || nombre.innerText;
                rows[i].style.display = txtValue.toLowerCase().indexOf(input) > -1 ? "" : "none";
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const factual = new Date();
        const anho = factual.getFullYear();
        const mes = String(factual.getMonth() + 1).padStart(2, '0'); 
        const dia = String(factual.getDate()).padStart(2, '0'); 
        const FechaActual = `${anho}-${mes}-${dia}`;
        const fechaInicioInput = document.querySelector('input[name="fecha_inicio"]');
        const fechaEntregaInput = document.querySelector('input[name="fecha_entrega"]');

        fechaInicioInput.setAttribute('min', FechaActual);

        fechaInicioInput.addEventListener('change', function () {
            const selectedFechaInicio = new Date(this.value); 

            if (!isNaN(selectedFechaInicio.getTime())) {
                const fechaMinimaEntrega = selectedFechaInicio.toISOString().split('T')[0];
                fechaEntregaInput.setAttribute('min', fechaMinimaEntrega);

                if (new Date(fechaEntregaInput.value) < selectedFechaInicio) {
                    fechaEntregaInput.value = '';
                }
            }
        });

        // Validar que el OP sea mayor a 0
        const opInput = document.querySelector('input[name="nombre"]');
        const form = opInput.closest('form');
        form.addEventListener('submit', function(event){
            const op = parseInt(opInput.value, 10);
            if(op <= 0){
                event.preventDefault();
                alert('La OP debe ser mayor a 0');
            }
        });
    });
</script>

<?php require_once '../../footer.php'; ?>

</body>
</html>
