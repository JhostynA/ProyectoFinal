<?php require_once '../../contenido.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Listado de Operaciones</h1>
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
                        <th>Operación</th>
                        <th>Precio</th>
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
                        <label for="operacion" class="form-label">Operación</label>
                        <input type="text" class="form-control" id="operacion" name="operacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precio" name="precio" required step="0.01">
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
            <label>Operacion</label>
            <input type="text" class="form-control" id="operacionEditar">
          </div>
          <div class="form-group">
            <label>Precio</label>
            <input type="text" class="form-control" id="precioEditar">
          </div>
          <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button> 
        </form>
      </div>
    </div>
  </div>
</div>

<!-- fin del modal -->



<script>
$(document).ready(function() {

  var modalAgregar = document.getElementById('modalAgregar');
    modalAgregar.addEventListener('hidden.bs.modal', function () {
        document.getElementById('formAgregar').reset();
    });

    var table = $('#tablaDatos').DataTable({
        "ajax": {
            "url": "../../controllers/operaciones/listar.controllers.php",
            "dataSrc": ""
        },
        "columns": [ 
            { "data": "operacion" },
            { "data": "precio" },
            {
                "data": null, 
                "render": function(data, type, row) {
                    return `
                        <button class="btn btn-warning btn-sm btnEditar" data-idoperacion="${row.idoperacion}"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-danger btn-sm btnEliminar" data-idoperacion="${row.idoperacion}"><i class="fa-solid fa-trash"></i></button>
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

        var operacion = $('#operacion').val();
        var precio = $('#precio').val();

        $.ajax({
            url: "../../controllers/operaciones/agregar.controllers.php",
            type: "POST",
            data: { operacion: operacion, precio: precio },
            success: function(response) {
                var data = JSON.parse(response);

                if (data.status === 'success') {
                    $('#modalAgregar').modal('hide');
                    $('.modal-backdrop').remove(); 
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
      var idoperacion = $(this).data('idoperacion');
      if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
        $.ajax({
          url: "../../controllers/operaciones/eliminar.controllers.php", 
          type: "POST",
          data: { idoperacion: idoperacion },
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
      var idoperacion = $(this).data('idoperacion');
      
      $.ajax({
        url: '../../controllers/operaciones/registroporid.controllers.php',
        type: 'POST',
        data: { idoperacion: idoperacion },
        success: function(response) {
          var data = JSON.parse(response);
          
          if (data.status === 'success') {
            $('#idEditar').val(data.registro.idoperacion);
            $('#operacionEditar').val(data.registro.operacion);
            $('#precioEditar').val(data.registro.precio);
            
            $('#modalEditar').modal('show');
          } else {
            alert(data.message);
          }
        }
      });
    });

    $('#formEditar').submit(function(event) {
      event.preventDefault();
      
      var idoperacion = $('#idEditar').val();
      var operacion = $('#operacionEditar').val();
      var precio = $('#precioEditar').val();
      
      $.ajax({
        url: '../../controllers/operaciones/actualizar.controllers.php',
        type: 'POST',
        data: {
          idoperacion: idoperacion,
          operacion: operacion,
          precio: precio
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


});
</script>

<?php require_once '../../footer.php'; ?>

</body>
</html>
