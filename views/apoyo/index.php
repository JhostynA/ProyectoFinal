<?php require_once '../../contenido.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Apoyos</title>
    <!-- CSS de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS de DataTables -->
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">
    <h2 class="text-center">Lista de Apoyos</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalRegistrar">Registrar Nuevo Apoyo</button>
    <table id="tablaApoyos" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Nombres</th>
                <th>DNI</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se cargarán los apoyos mediante AJAX -->
        </tbody>
    </table>
</div>

<!-- Modal para registrar un nuevo apoyo -->
<div class="modal fade" id="modalRegistrar" tabindex="-1" role="dialog" aria-labelledby="modalRegistrarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarLabel">Registrar Nuevo Apoyo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formRegistrar">
                    <div class="form-group">
                        <label for="ape_paterno">Apellido Paterno</label>
                        <input type="text" class="form-control" id="ape_paterno" required>
                    </div>
                    <div class="form-group">
                        <label for="ape_materno">Apellido Materno</label>
                        <input type="text" class="form-control" id="ape_materno" required>
                    </div>
                    <div class="form-group">
                        <label for="nombres">Nombres</label>
                        <input type="text" class="form-control" id="nombres" required>
                    </div>
                    <div class="form-group">
                        <label for="documento">DNI</label>
                        <input type="text" class="form-control" id="documento" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript de jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- JavaScript de Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- JavaScript de DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializar DataTable
    var tablaApoyos = $('#tablaApoyos').DataTable({
        ajax: {
            url: '../../controllers/apoyo/apoyo.controller.php', // Asegúrate de que la ruta sea correcta
            method: 'GET',
            dataSrc: ''
        },
        columns: [
            { data: 'idapoyo' },
            { data: 'ape_paterno' },
            { data: 'ape_materno' },
            { data: 'nombres' },
            { data: 'documento' },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-warning btn-editar" data-id="${row.idapoyo}">Editar</button>
                        <button class="btn btn-danger btn-eliminar" data-id="${row.idapoyo}">Eliminar</button>
                    `;
                }
            }
        ]
    });

    // Manejar el envío del formulario para registrar un nuevo apoyo
    $('#formRegistrar').on('submit', function(event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        var data = {
            ape_paterno: $('#ape_paterno').val(),
            ape_materno: $('#ape_materno').val(),
            nombres: $('#nombres').val(),
            documento: $('#documento').val()
        };

        $.ajax({
            url: '../../controllers/apoyo/apoyo.controller.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                alert(response.mensaje); // Mostrar mensaje de éxito
                $('#modalRegistrar').modal('hide'); // Cerrar el modal
                tablaApoyos.ajax.reload(); // Recargar la tabla
            },
            error: function(xhr) {
                alert(xhr.responseJSON.error); // Mostrar mensaje de error
            }
        });
    });

    // Manejar el clic en el botón de eliminar
    $('#tablaApoyos').on('click', '.btn-eliminar', function() {
        var idapoyo = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar este apoyo?')) {
            $.ajax({
                url: '../../controllers/apoyo/apoyo.controller.php',
                method: 'DELETE',
                contentType: 'application/json',
                data: JSON.stringify({ idapoyo: idapoyo }),
                success: function(response) {
                    alert(response.mensaje); // Mostrar mensaje de éxito
                    tablaApoyos.ajax.reload(); // Recargar la tabla
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.error); // Mostrar mensaje de error
                }
            });
        }
    });

    // Puedes agregar funcionalidad para editar apoyos aquí

});
</script>
</body>
</html>

<?php require_once '../../footer.php'; ?>

</body>
</html>