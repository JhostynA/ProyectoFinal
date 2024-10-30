<?php require_once '../../contenido.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Pagos</h1>

    <!-- Formulario de Registro de Pago -->
    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            <h2 class="h5">Registrar Pago</h2>
        </div>
        <div class="card-body">
            <form id="formPago">
                <input type="hidden" id="idpersona" name="idpersona">
                <div class="mb-3">
                    <label for="searchInput" class="form-label">Trabajador:</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar trabajador..." list="personasList" required>
                    <datalist id="personasList"></datalist>
                </div>
                <div class="mb-3">
                    <label for="searchOperaciones" class="form-label">Operación:</label>
                    <input type="text" class="form-control" id="searchOperaciones" placeholder="Buscar operación..." list="operacionesList" required>
                    <datalist id="operacionesList"></datalist>
                </div>
                <div class="mb-3">
                    <label for="prendas_realizadas" class="form-label">Prendas Realizadas:</label>
                    <input type="number" class="form-control" id="prendas_realizadas" name="prendas_realizadas" required>
                </div>
                <button type="submit" class="btn btn-success">Registrar Pago</button>
            </form>
        </div>
    </div>

    <!-- Tabla de Pagos -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h2 class="h5">Lista de Pagos</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tablaPagos">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Pago</th>
                            <th>Trabajador</th>
                            <th>Operación</th>
                            <th>Prendas Realizadas</th>
                            <th>Precio Operación</th>
                            <th>Total Pago</th>
                            <th>Fecha Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los pagos se cargarán aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS (Opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Cargar pagos al inicio
        cargarPagos();

        // Manejar el envío del formulario
        $('#formPago').on('submit', function(e) {
            e.preventDefault(); // Prevenir el comportamiento por defecto del formulario
            $.ajax({
                url: '../../controllers/pagos/pagos.controller.php',
                type: 'POST',
                data: {
                    operacion: 'register',
                    idpersona: $('#idpersona').val(), // Envía el ID de la persona
                    idoperacion: $('#searchOperaciones').val(),
                    prendas_realizadas: $('#prendas_realizadas').val()
                },
                success: function(response) {
                    console.log('Respuesta del servidor:', response); // Agrega esto
                    alert('Pago registrado: ' + response);
                    $('#formPago')[0].reset(); // Resetear el formulario
                    cargarPagos(); // Recargar la lista de pagos
                },
                error: function(err) {
                    alert('Error al registrar el pago');
                }
            });
        });

        // Función para cargar todos los pagos
        function cargarPagos() {
            $.ajax({
                url: '../../controllers/pagos/pagos.controller.php',
                type: 'GET',
                data: {
                    operacion: 'getAll'
                },
                success: function(data) {
                    const pagos = JSON.parse(data);
                    const tbody = $('#tablaPagos tbody');
                    tbody.empty(); // Limpiar la tabla antes de cargar nuevos datos
                    pagos.forEach(pago => {
                        tbody.append(`
                        <tr>
                            <td>${pago.idpago}</td>
                            <td>${pago.nombre_trabajador}</td>
                            <td>${pago.operacion}</td>
                            <td>${pago.prendas_realizadas}</td>
                            <td>${pago.precio_operacion}</td>
                            <td>${pago.total_pago}</td>
                            <td>${pago.fecha_pago}</td>
                        </tr>
                    `);
                    });
                },
                error: function(err) {
                    alert('Error al cargar los pagos');
                }
            });
        }

        // Función para buscar operaciones
        $('#searchOperaciones').on('input', function() {
            const termino = $(this).val();

            if (termino.length > 2) { // Buscar solo si hay más de 2 caracteres
                $.ajax({
                    url: '../../controllers/pagos/pagos.controller.php',
                    type: 'GET',
                    data: {
                        operacion: 'buscarOperaciones',
                        termino: termino
                    },
                    success: function(response) {
                        const operaciones = JSON.parse(response);
                        let suggestions = '';

                        operaciones.forEach(operacion => {
                            suggestions += `<option value="${operacion.operacion}"></option>`;
                        });

                        $('#operacionesList').html(suggestions); // Actualiza el datalist con las opciones
                    },
                    error: function(err) {
                        console.error('Error al buscar operaciones', err);
                    }
                });
            }
        });

        // Función para buscar personas
        $('#searchInput').on('input', function() {
            const term = $(this).val();

            if (term.length > 2) { // Buscar solo si hay más de 2 caracteres
                $.ajax({
                    url: '../../controllers/pagos/pagos.controller.php',
                    type: 'GET',
                    data: {
                        operacion: 'buscarPersonas',
                        term: term
                    },
                    success: function(response) {
                        const personas = JSON.parse(response);
                        let suggestions = '';

                        personas.forEach(persona => {
                            suggestions += `<option value="${persona.nombres} ${persona.apepaterno} ${persona.apematerno}" data-id="${persona.idpersona}"></option>`;
                        });

                        $('#personasList').html(suggestions); // Actualiza el datalist con las opciones
                    },
                    error: function(err) {
                        console.error('Error al buscar personas', err);
                    }
                });
            }
        });

        $('#searchInput').on('change', function() {
            const selectedOption = $('#personasList option[value="' + $(this).val() + '"]');
            if (selectedOption.length > 0) {
                $('#idpersona').val(selectedOption.data('id')); // Asigna el ID al campo oculto
                console.log('ID de persona seleccionado:', $('#idpersona').val()); // Verifica el ID
            } else {
                $('#idpersona').val(''); // Limpiar el campo si no hay coincidencia
            }
        });

    });
</script>

<?php require_once '../../footer.php'; ?>