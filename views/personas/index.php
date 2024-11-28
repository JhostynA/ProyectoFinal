<?php require_once '../../contenido.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Listado de Trabajadores</h1>
    <div class="card shadow">
        <div class="card-body">
            <div class="mb-5">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                    Agregar nuevo trabajador
                </button>
            </div>
            <table id="tablaDatos" class="table table-striped table-hover w-100">
                <thead class="table-dark">
                    <tr>
                        <th>Apellidos</th>
                        <th>Nombre</th>
                        <th>Telefono</th>
                        <th>Tipo de Documento</th>
                        <th>Numero de documento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal para agregar un registro -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarLabel">Agregar Trabajador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregar">

                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                    </div>

                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                    </div>

                    <div class="mb-3">
                        <label for="tipodocumento" class="form-label">Tipo documento</label>
                        <select type="text" class="form-control" id="tipodocumento" name="tipodocumento" required>
                            <option value="">Seleccion un tipo de documento</option>
                            <option value="DNI">DNI</option>
                            <option value="PST">Pasaporte</option>
                            <option value="CEX">Carne de extranjeria</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="numerodocumento" class="form-label">Numero de documento</label>
                        <input type="text" class="form-control" id="numerodocumento" name="numerodocumento" required>
                    </div>

                    <button type="submit" class="btn btn-success">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- fin del modal -->


<!-- Modal para Editar Registro -->

<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditar">
                    <input type="hidden" id="idEditar">
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" class="form-control" id="apellidosEditar">
                    </div>
                    <div class="form-group">
                        <label>Nombres</label>
                        <input type="text" class="form-control" id="nombresEditar">
                    </div>
                    <div class="form-group">
                        <label>Telefono</label>
                        <input type="text" class="form-control" id="telefonoEditar">
                    </div>
                    <div class="form-group">
                        <label>Tipo documento</label>
                        <select type="text" class="form-control" id="tipodocumentoEditar">
                            <option value="">Seleccion un tipo de documento</option>
                            <option value="DNI">DNI</option>
                            <option value="PST">Pasaporte</option>
                            <option value="CEX">Carne de extranjeria</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Numero de documento</label>
                        <input type="text" class="form-control" id="numerodocumentoEditar">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- fin del modal -->


<script>
    var modalAgregar = document.getElementById('modalAgregar');
    modalAgregar.addEventListener('hidden.bs.modal', function() {
        document.getElementById('formAgregar').reset();
    });


    $(document).ready(function() {
        var table = $('#tablaDatos').DataTable({
            "ajax": {
                "url": "../../controllers/personas/listar.controllers.php",
                "dataSrc": ""
            },
            "columns": [
                {
                    "data": "apellidos"
                },
                {
                    "data": "nombres"
                },
                {
                    "data": "telefono"
                },
                {
                    "data": "tipodoc"
                },
                {
                    "data": "numdoc"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <button class="btn btn-warning btn-sm btnEditar" data-idpersona="${row.idpersona}"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="btn btn-danger btn-sm btnEliminar" data-idpersona="${row.idpersona}"><i class="fa-solid fa-trash"></i></button>
                        `;
                    }
                }
            ],
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });

        //PARA AGREGAR UN REGISTRO
        $('#formAgregar').on('submit',async function(e) {
            e.preventDefault();

            if (await showConfirm("¿Estas seguro de guardar?")){

            var apellidos = $('#apellidos').val();
            var nombres = $('#nombres').val();
            var telefono = $('#telefono').val();
            var tipodoc = $('#tipodocumento').val();
            var numdoc = $('#numerodocumento').val();

            $.ajax({
                url: "../../controllers/personas/agregar.controllers.php",
                type: "POST",
                data: {
                    apellidos: apellidos,
                    nombres: nombres,
                    telefono: telefono,
                    tipodoc: tipodoc,
                    numdoc: numdoc
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data.status === 'success') {
                        $('#modalAgregar').modal('hide');
                        $('.modal-backdrop').remove(); // Con esto eliminamos el overlay oscuro
                        $('body').removeClass('modal-open')
                        $('#formAgregar')[0].reset();
                        table.ajax.reload();
                    } else {
                        alert(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }
            });
            }else{
                showToast("Error al guardar", "error", "ERROR") 
            }
        });

        // Esta función restablecer el scroll al body
        $('#modalAgregar').on('hidden.bs.modal', function() {
            $('body').css('overflow', 'auto');
        });

        //PARA ELIMINAR UN REGISTRO
        $('#tablaDatos tbody').on('click', '.btnEliminar', function() {
            var idpersona = $(this).data('idpersona');
            if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
                $.ajax({
                    url: "../../controllers/personas/eliminar.controllers.php",
                    type: "POST",
                    data: {
                        idpersona: idpersona
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            table.ajax.reload();
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error: " + error);
                    }
                });
            }
        });


        //PARA ACTUALIZAR UN REGISTRO

        $('#tablaDatos tbody').on('click', '.btnEditar', function() {
            var idpersona = $(this).data('idpersona');

            $.ajax({
                url: '../../controllers/personas/registroporid.controllers.php',
                type: 'POST',
                data: {
                    idpersona: idpersona
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data.status === 'success') {
                        $('#idEditar').val(data.registro.idpersona);
                        $('#apellidosEditar').val(data.registro.apellidos);
                        $('#nombresEditar').val(data.registro.nombres);
                        $('#telefonoEditar').val(data.registro.telefono);
                        $('#tipodocumentoEditar').val(data.registro.tipodoc);
                        $('#numerodocumentoEditar').val(data.registro.numdoc);

                        $('#modalEditar').modal('show');
                    } else {
                        alert(data.message);
                    }
                }
            });
        });

        $('#formEditar').submit(function(event) {
            event.preventDefault();

            var idpersona = $('#idEditar').val();
            var apellidos = $('#apellidosEditar').val();
            var nombres = $('#nombresEditar').val();
            var telefono = $('#telefonoEditar').val();
            var tipodoc = $('#tipodocumentoEditar').val();
            var numdoc = $('#numerodocumentoEditar').val();

            $.ajax({
                url: '../../controllers/personas/actualizar.controllers.php',
                type: 'POST',
                data: {
                    idpersona: idpersona,
                    apellidos: apellidos,
                    nombres: nombres,
                    telefono: telefono,
                    tipodoc: tipodoc,
                    numdoc: numdoc
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data.status === 'success') {
                        $('#modalEditar').modal('hide');
                        table.ajax.reload();
                    } else {
                        alert(data.message);
                    }
                }
            });
        });


    })
</script>

<?php require_once '../../footer.php'; ?>

</body>

</html>