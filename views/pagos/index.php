<?php require_once '../../contenido.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Pagos y Operaciones</title>
  <!-- Vinculamos Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">

  <style>
    /* Estilos para las sugerencias */
    #suggestions_persona,
    #suggestions_operacion,
    #suggestions_op,
    #suggestions_secuencia {
      display: none;
      position: absolute;
      width: 100%;
      z-index: 1000;
      max-height: 150px;
      overflow-y: auto;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin-top: 5px;
      padding: 0;
    }

    .list-group-item {
      padding: 10px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    /* Resaltar opción seleccionada */
    .list-group-item:hover {
      background-color: #007bff;
      color: white;
    }

    /* Aseguramos que los inputs no se solapen con las sugerencias */
    .position-relative {
      position: relative;
    }

    /* Diseño de las sugerencias cuando están visibles */
    #suggestions_persona,
    #suggestions_operacion,
    #suggestions_op,
    #suggestions_secuencia {
      display: block;
    }
  </style>
</head>

<body>

  <div class="container mt-5">

    <!-- Título principal -->
    <h2 class="mb-4 text-center">Gestión de Pagos y Operaciones</h2>

    <!-- Formulario para registrar y actualizar pagos -->
    <div class="card shadow-lg">
      <div class="card-header bg-primary text-white">
        <h5>Registrar o Actualizar Pago</h5>
      </div>
      <div class="card-body">
        <form id="formPago" method="POST" novalidate>
          <div class="row">
            <!-- Campo Nombre Persona -->
            <div class="col-md-6 mb-3 position-relative">
              <label for="nombre_persona" class="form-label">Nombre Persona</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" id="nombre_persona" name="nombre_persona" placeholder="Buscar persona..." required>
              </div>
              <div class="invalid-feedback">Este campo es obligatorio.</div>
              <ul id="suggestions_persona" class="list-group position-absolute w-100" style="display: none;"></ul>
            </div>

            <!-- Campo Operación -->
            <div class="col-md-6 mb-3 position-relative">
              <label for="nombre_operacion" class="form-label">Nombre Operación</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-cogs"></i></span>
                <input type="text" class="form-control" id="nombre_operacion" name="nombre_operacion" placeholder="Buscar operación..." required>
              </div>
              <div class="invalid-feedback">Este campo es obligatorio.</div>
              <ul id="suggestions_operacion" class="list-group position-absolute w-100" style="display: none;"></ul>
            </div>
          </div>

          <div class="row">
            <!-- Campo OP -->
            <div class="col-md-6 mb-3 position-relative">
              <label for="nombre_op" class="form-label">Orden de Producción (OP)</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                <input type="text" class="form-control" id="nombre_op" name="nombre_op" placeholder="Buscar OP..." required>
              </div>
              <div class="invalid-feedback">Este campo es obligatorio.</div>
              <ul id="suggestions_op" class="list-group position-absolute w-100" style="display: none;"></ul>
            </div>

            <!-- Campo Número de Secuencia -->
            <div class="col-md-6 mb-3 position-relative">
              <label for="numSecuencia" class="form-label">Número de Secuencia</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-clipboard"></i></span>
                <input type="text" class="form-control" id="numSecuencia" name="numSecuencia" placeholder="Secuencia..." required disabled>
              </div>
              <div class="invalid-feedback">Este campo es obligatorio.</div>
              <ul id="suggestions_secuencia" class="list-group position-absolute w-100" style="display: none;"></ul>
            </div>
          </div>

          <div class="mb-3">
            <label for="prendas_realizadas" class="form-label">Prendas Realizadas</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa fa-tshirt"></i></span>
              <input type="number" class="form-control" id="prendas_realizadas" name="prendas_realizadas" required>
            </div>
            <div class="invalid-feedback">Este campo es obligatorio.</div>
          </div>
        


          <button type="submit" class="btn btn-primary w-100">Registrar Pago</button>
        </form>
      </div>
    </div>

    <!-- Tabla de Pagos -->
    <div class="card mt-4">
      <div class="card-header bg-info text-white">
        <h5>Pagos Registrados</h5>
      </div>
      <div class="card-body">
        <table id="tablaPagos" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Persona</th>
              <th>Operación</th>
              <th>Orden de Producción</th>
              <th>Secuencia</th>
              <th>Prendas Realizadas</th>
              <th>Total Pago</th>
              <th>Fecha de Creación</th>
            </tr>
          </thead>
          <tbody id="pagosLista">
            <!-- Los pagos se cargarán aquí mediante JavaScript -->
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Funciones para los campos de búsqueda, validación, etc.
      // Todo el JavaScript de interacción permanece igual, asegurándose de actualizar el contenido de la tabla
      // y validando los campos del formulario.
    });
  </script>

</body>

</html>
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    let selectedOpId = null; // Variable para almacenar el id de la OP seleccionada
    let idpersona = document.querySelector("#nombre_persona");
    let idoperacion = document.querySelector("#nombre_operacion");
    let idop = document.querySelector("#nombre_op");
    let idsecu = document.querySelector("#numSecuencia");
    let prendas = document.querySelector("#prendas_realizadas");
    let totalPago = document.querySelector("#total_pago");

    const valorPorPrenda = 10; // Suponiendo que el valor por prenda es 10

    // Función para calcular el total a pagar
    function calcularTotalPago() {
      const prendasRealizadas = parseInt(prendas.value);
      if (isNaN(prendasRealizadas) || prendasRealizadas <= 0) {
        totalPago.value = '0.00'; // Si el valor no es válido, mostrar 0
      } else {
        const total = prendasRealizadas * valorPorPrenda;
        totalPago.value = `$${total.toFixed(2)}`; // Mostrar el total con dos decimales
      }
    }

    // Asignamos el evento para recalcular el total cuando cambie el valor de prendas realizadas
    prendas.addEventListener('input', calcularTotalPago);

    // Función para buscar personas
    function buscarPersona() {
      const query = document.getElementById('nombre_persona').value;
      if (query.trim() === '') {
        return;
      }

      fetch(`../../controllers/pagos/personas.controller.php?operation=BuscarPersona&query=${query}`)
        .then(response => response.json())
        .then(data => {
          const suggestions = document.getElementById('suggestions_persona');
          suggestions.innerHTML = '';
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
            suggestions.style.display = 'none';
          } else {
            data.forEach(persona => {
              const li = document.createElement('li');
              li.classList.add('list-group-item');
              li.textContent = `${persona.nombres} ${persona.apepaterno} ${persona.apematerno}`;
              li.onclick = function() {
                seleccionarPersona(persona.idpersona, `${persona.nombres} ${persona.apepaterno} ${persona.apematerno}`);
                idpersona.setAttribute('data-id', persona.idpersona);
              };
              suggestions.appendChild(li);
            });
            suggestions.style.display = 'block';
          }
        })
        .catch(error => {
          console.error('Error al buscar personas:', error);
          Swal.fire('Error', 'Hubo un problema al realizar la búsqueda', 'error');
        });
    }

    // Función para seleccionar persona
    function seleccionarPersona(id, nombre) {
      document.getElementById('nombre_persona').value = nombre;
      document.getElementById('suggestions_persona').style.display = 'none';
    }

    // Función para buscar operaciones
    function buscarOperacion() {
      const query = document.getElementById('nombre_operacion').value;
      if (query.trim() === '') {
        return;
      }

      fetch(`../../controllers/pagos/operaciones.controller.php?operation=listar&query=${query}`)
        .then(response => response.json())
        .then(data => {
          const suggestions = document.getElementById('suggestions_operacion');
          suggestions.innerHTML = '';
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
            suggestions.style.display = 'none';
          } else {
            data.forEach(operacion => {
              const li = document.createElement('li');
              li.classList.add('list-group-item');
              li.textContent = `${operacion.operacion}`;
              li.onclick = function() {
                seleccionarOperacion(operacion.idoperacion, operacion.operacion);
                idoperacion.setAttribute('operacion', operacion.idoperacion);
              };
              suggestions.appendChild(li);
            });
            suggestions.style.display = 'block';
          }
        })
        .catch(error => {
          console.error('Error al buscar operaciones:', error);
          Swal.fire('Error', 'Hubo un problema al realizar la búsqueda de operaciones', 'error');
        });
    }

    // Función para seleccionar operación
    function seleccionarOperacion(id, nombre) {
      document.getElementById('nombre_operacion').value = nombre;
      document.getElementById('suggestions_operacion').style.display = 'none';
      document.getElementById('nombre_op').disabled = false; // Habilitar el campo de OP
      selectedOpId = id; // Guardar el ID de la operación seleccionada
      buscarOp();
    }

    // Función para buscar OPs
    function buscarOp() {
      const query = document.getElementById('nombre_op').value;
      if (query.trim() === '') {
        return;
      }

      fetch(`../../controllers/pagos/ops.controller.php?operation=BuscarOp&query=${query}`)
        .then(response => response.json())
        .then(data => {
          const suggestions = document.getElementById('suggestions_op');
          suggestions.innerHTML = '';
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
            suggestions.style.display = 'none';
          } else {
            data.forEach(op => {
              const li = document.createElement('li');
              li.classList.add('list-group-item');
              li.textContent = `${op.nombre}`;
              li.onclick = function() {
                seleccionarOp(op.id, op.nombre);
                idop.setAttribute('orden_produccion', op.id);
              };
              suggestions.appendChild(li);
            });
            suggestions.style.display = 'block';
          }
        })
        .catch(error => {
          console.error('Error al buscar OP:', error);
          Swal.fire('Error', 'Hubo un problema al realizar la búsqueda de OP', 'error');
        });
    }

    // Función para seleccionar OP
    function seleccionarOp(id, nombre) {
      selectedOpId = id;
      document.getElementById('nombre_op').value = nombre;
      document.getElementById('suggestions_op').style.display = 'none';
      buscarSecuencias(id); // Buscar secuencias por OP seleccionada
    }

    // Función para buscar secuencias
    function buscarSecuencias(idop) {
      fetch(`../../controllers/pagos/secuencias.controller.php?operation=listarSecuenciasPorOp&opId=${idop}`)
        .then(response => response.json())
        .then(data => {
          const suggestions = document.getElementById('suggestions_secuencia');
          suggestions.innerHTML = '';
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
            suggestions.style.display = 'none';
          } else {
            data.forEach(secuencia => {
              const li = document.createElement('li');
              li.classList.add('list-group-item');
              li.textContent = `Secuencia: ${secuencia.numSecuencia}`;
              li.onclick = function() {
                seleccionarSecuencia(secuencia.id, secuencia.numSecuencia);
                idsecu.setAttribute('secuencia', secuencia.secuenciaid);
              };
              suggestions.appendChild(li);
            });
            suggestions.style.display = 'block';
          }
        })
        .catch(error => {
          console.error('Error al buscar secuencias:', error);
          Swal.fire('Error', 'Hubo un problema al realizar la búsqueda de secuencias', 'error');
        });
    }

    // Función para seleccionar secuencia
    function seleccionarSecuencia(id, numero) {
      document.getElementById('numSecuencia').value = numero;
      document.getElementById('suggestions_secuencia').style.display = 'none';
    }

    // Asociamos las funciones de búsqueda a los campos de texto
    document.getElementById('nombre_persona').addEventListener('input', buscarPersona);
    document.getElementById('nombre_operacion').addEventListener('input', buscarOperacion);
    document.getElementById('nombre_op').addEventListener('input', buscarOp);

    // Registrar pago
    async function registraPagos() {
      const params = new FormData();
      params.append('operation', 'register');
      params.append('_idpersona', idpersona.getAttribute('data-id'));
      params.append('_idoperacion', idoperacion.getAttribute('operacion'));
      params.append('_idop', idop.getAttribute('orden_produccion'));
      params.append('_idsecuencia', idsecu.getAttribute('secuencia'));
      params.append('_prendas_realizadas', prendas.value);

      const options = {
        method: 'POST',
        body: params
      }

      try {
        const response = await fetch(`../../controllers/pagos/pagos.controller.php`, options)
        const data = await response.json();
        return data;
      } catch (e) {
        console.error(e);
      }
    }

    // Enviar el formulario para registrar el pago
    document.getElementById('formPago').addEventListener('submit', async function(event) {
      event.preventDefault();
      const data = await registraPagos();
      console.log(data);
    });

    // Función para listar los pagos
    async function listarPagos() {
      try {
        const response = await fetch('../../controllers/pagos/pagos.controller.php?operation=listarPagos');
        const data = await response.json();

        if (data.error) {
          Swal.fire('Error', data.error, 'error');
          return;
        }

        const pagosLista = document.getElementById('pagosLista');
        pagosLista.innerHTML = '';

        data.forEach((pago, index) => {
          const row = document.createElement('tr');
          row.innerHTML = `
          <td>${index + 1}</td>
          <td>${pago.nombre_persona}</td>
          <td>${pago.nombre_operacion}</td>
          <td>${pago.nombre_op}</td>
          <td>${pago.nombre_secuencia}</td>
          <td>${pago.prendas_realizadas}</td>
          <td>$${parseFloat(pago.total_pago).toFixed(2)}</td>
          <td>${new Date(pago.create_at).toLocaleString()}</td>
        `;
          pagosLista.appendChild(row);
        });
      } catch (error) {
        console.error('Error al listar los pagos:', error);
        Swal.fire('Error', 'Hubo un problema al listar los pagos', 'error');
      }
    }

    listarPagos();

  });
  
</script>

</body>

</html>