<?php require_once '../../contenido.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Listado de Personas</h1>
    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                    Agregar Registro
                </button>
            </div>
            <table id="tablaDatos" class="table table-striped table-hover w-100">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Apellido P</th>
                        <th>Apellido M</th>
                        <th>Nombre</th>
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
                <h5 class="modal-title" id="modalAgregarLabel">Agregar Nuevo Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregar">

                    <div class="mb-3">
                        <label for="apepaterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apepaterno" name="apepaterno" required>
                    </div>

                    <div class="mb-3">
                        <label for="apematerno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apematerno" name="apematerno" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" required>
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
            <label>Apellido Paterno</label>
            <input type="text" class="form-control" id="apepaternoEditar">
          </div>
          <div class="form-group">
            <label>Apellido Materno</label>
            <input type="text" class="form-control" id="apematernoEditar">
          </div>
          <div class="form-group">
            <label>Nombres</label>
            <input type="text" class="form-control" id="nombresEditar">
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
        modalAgregar.addEventListener('hidden.bs.modal', function () {
        document.getElementById('formAgregar').reset();
    });


    $(document).ready(function(){
        var table = $('#tablaDatos').DataTable({
            "ajax": {
                "url": "../../controllers/personas/listar.controllers.php",
                "dataSrc": ""
            },
            "columns": [
                { "data": "idpersona" },  
                { "data": "apepaterno" },
                { "data": "apematerno" },
                { "data": "nombres"},
                {
                    "data": null, 
                    "render": function(data, type, row) {
                        return `
                            <button class="btn btn-warning btn-sm btnEditar" data-idpersona="${row.idpersona}">Editar</button>
                            <button class="btn btn-danger btn-sm btnEliminar" data-idpersona="${row.idpersona}">Eliminar</button>
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
    $('#formAgregar').on('submit', function(e) {
        e.preventDefault();

        var apepaterno = $('#apepaterno').val();
        var apematerno = $('#apematerno').val();
        var nombres = $('#nombres').val();

        $.ajax({
            url: "../../controllers/personas/agregar.controllers.php",
            type: "POST",
            data: { apepaterno: apepaterno, apematerno: apematerno, nombres: nombres},
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
    });

    // Esta función restablecer el scroll al body
    $('#modalAgregar').on('hidden.bs.modal', function () {
        $('body').css('overflow', 'auto'); 
    });


    //PARA ELIMINAR UN REGISTRO
    $('#tablaDatos tbody').on('click', '.btnEliminar', function() {
        var idpersona = $(this).data('idpersona');
        if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
        $.ajax({
            url: "../../controllers/personas/eliminar.controllers.php", 
            type: "POST",
            data: { idpersona: idpersona },
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
        data: { idpersona: idpersona },
        success: function(response) {
            var data = JSON.parse(response);
            
            if (data.status === 'success') {
            $('#idEditar').val(data.registro.idpersona);
            $('#apepaternoEditar').val(data.registro.apepaterno);
            $('#apematernoEditar').val(data.registro.apematerno);
            $('#nombresEditar').val(data.registro.nombres);
            
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
        var apepaterno = $('#apepaternoEditar').val();
        var apematerno = $('#apematernoEditar').val();
        var nombres = $('#nombresEditar').val();
        
        $.ajax({
        url: '../../controllers/personas/actualizar.controllers.php',
        type: 'POST',
        data: {
            idpersona: idpersona,
            apepaterno: apepaterno,
            apematerno: apematerno,
            nombres: nombres
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