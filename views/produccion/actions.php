<?php require_once '../../contenido.php'; 
require_once '../../models/produccion/ActionModel.php';
$secuenciasModel = new ActionModel();

?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">PRODUCCIÓN</h1>

    <div class="d-flex justify-content-between mb-3 align-items-center">
        <input type="text" id="searchInput" class="form-control mr-2" placeholder="Buscar por OP..." style="width: 200px;">
        <button type="button" class="btn btn-success shadow" data-toggle="modal" data-target="#createActionModal">
            Nueva Producción
        </button>
    </div>

    <table id="actionsTable" class="table table-bordered shadow-lg">
        <thead class="thead-dark">
            <tr>
                <th style="width: 80px;">Secuencia</th>
                <th class="text-center">OP</th>
                <th style="width: 120px;" class="text-center">Fecha Inicio</th>
                <th style="width: 120px;" class="text-center">Fecha Entrega</th>
                <th colspan="4" class="text-center">Tallas</th>
                <th style="width: 100px;">Total Prendas</th>
                <th class="text-center">Progreso</th>
                <th class="text-center">PDF</th>
            </tr>
            <tr>
                <th colspan="4"></th>
                <th>S</th>
                <th>M</th>
                <th>L</th>
                <th>XL</th>
                <th colspan="3"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($actions) && !empty($actions)): ?>
                <?php foreach ($actions as $action): ?>
                    <?php 
                    $tallasTotales = $secuenciasModel->getTotalPrendasByActionId($action['id']);
                    ?>
                    <tr class="table-hover action-row" data-op="<?= htmlspecialchars($action['nombre']) ?>">
                        <td class="text-center"><button class="btn btn-link" onclick="toggleDetails(this)">▶</button></td>
                        <td class="text-center"><?= htmlspecialchars($action['nombre']) ?></a></td>
                        <td><?= htmlspecialchars($action['fecha_inicio']) ?></td>
                        <td><?= htmlspecialchars($action['fecha_entrega']) ?></td>
                        <td><?= htmlspecialchars($action['talla_s']) ?></td>
                        <td><?= htmlspecialchars($action['talla_m']) ?></td>
                        <td><?= htmlspecialchars($action['talla_l']) ?></td>
                        <td><?= htmlspecialchars($action['talla_xl']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($action['talla_s'] + $action['talla_m'] + $action['talla_l'] + $action['talla_xl']) ?></td>
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
                                    <?= $action['porcentaje'] ?>%
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="<?= $host ?>/views/produccion/indexP.php?action=viewPDF&id=<?= $action['id'] ?>" class="btn btn-outline-danger">Ver PDF</a>
                        </td>
                    </tr>
                    
                    <tr class="details" style="display: none; background-color: #f9f9f9;">
                    <td colspan="10">
                        <!-- Contenedor con clase d-flex para la alineación -->
                        <div class="d-flex align-items-center">
                            <!-- Botón a la izquierda -->
                            <button class="btn btn-primary btn-sm mr-3" 
                                    data-toggle="modal" 
                                    data-target="#createSequenceModal" 
                                    data-op-id="<?= $action['id'] ?>"
                                    data-fecha-inicio="<?= htmlspecialchars($action['fecha_inicio']) ?>" 
                                    data-fecha-entrega="<?= htmlspecialchars($action['fecha_entrega']) ?>"
                                    data-talla_s="<?= htmlspecialchars($action['talla_s']) ?>"
                                    data-talla_m="<?= htmlspecialchars($action['talla_m']) ?>"
                                    data-talla_l="<?= htmlspecialchars($action['talla_l']) ?>"
                                    data-talla_xl="<?= htmlspecialchars($action['talla_xl']) ?>"
                                    data-talla_s_registrada="<?= htmlspecialchars($tallasTotales['talla_s']) ?>"
                                    data-talla_m_registrada="<?= htmlspecialchars($tallasTotales['talla_m']) ?>"
                                    data-talla_l_registrada="<?= htmlspecialchars($tallasTotales['talla_l']) ?>"
                                    data-talla_xl_registrada="<?= htmlspecialchars($tallasTotales['talla_xl']) ?>">
                                Nueva Secuencia
                            </button>

                            <!-- Título centrado -->
                            <h5 class="m-0 mx-auto" style="font-size: 1.25rem; color: #333;">Secuencias de la OP <?= htmlspecialchars($action['nombre']) ?></h5>
                        </div>
                        
                        <!-- Tabla de secuencias -->
                        <table class="table table-sm rounded shadow-sm mt-3">
                            <thead class="thead-light" style="background-color: #007bff; color: #fff;">
                                <tr>
                                    <th>Num. Secuencia</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Final</th>
                                    <th>Prendas a Realizar</th>
                                    <th>Prendas Faltantes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sequences = $secuenciasModel->getSecuenciasByActionId($action['id']);
                                foreach ($sequences as $sequence): ?>
                                    <tr>
                                        <td><a href="<?= $host ?>/views/produccion/indexP.php?action=viewSecuencia&id=<?= $sequence['id'] ?>" class="text-primary">
                                                <?= htmlspecialchars($sequence['numSecuencia']) ?>              
                                            </a>
                                        </td>
                                        <td><?= htmlspecialchars($sequence['fechaInicio']) ?></td>
                                        <td><?= htmlspecialchars($sequence['fechaFinal']) ?></td>
                                        <td><?= htmlspecialchars($sequence['prendasArealizar']) ?></td>
                                        <td><?= htmlspecialchars($sequence['prendasFaltantes']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($sequences)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No hay secuencias disponibles para esta OP.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>


                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center text-muted">No hay producciones disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>



<!-- Modal para crear nueva secuencia -->
<div class="modal fade" id="createSequenceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Secuencia</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCreateSequence" method="POST" action="<?= $host ?>/views/produccion/indexP.php?action=createSequence">
                    <input type="hidden" name="idop" id="opIdInput">
                    <input type="hidden" name="talla_s_max" id="talla_s_max">
                    <input type="hidden" name="talla_m_max" id="talla_m_max">
                    <input type="hidden" name="talla_l_max" id="talla_l_max">
                    <input type="hidden" name="talla_xl_max" id="talla_xl_max">
                    
                    <div class="form-group" style="display:none;">
                        <label for="numSecuencia">Número de Secuencia:</label>
                        <input type="number" class="form-control" name="numSecuencia">
                    </div>
                    <div class="form-group">
                        <label for="fechaInicio">Fecha de Inicio:</label>
                        <input type="date" class="form-control" name="fechaInicio" id="fechaInicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaFinal">Fecha Final:</label>
                        <input type="date" class="form-control" name="fechaFinal" id="fechaFinal" required>
                    </div>
                    <div class="form-group">
                        <label for="talla_s">Cantidad Talla S (Máx: <span id="talla_s_limit"></span>):</label>
                        <input type="number" class="form-control" name="talla_s" min="0" id="talla_s_s" >
                    </div>
                    <div class="form-group">
                        <label for="talla_m">Cantidad Talla M (Máx: <span id="talla_m_limit"></span>):</label>
                        <input type="number" class="form-control" name="talla_m" min="0" id="talla_m_s" >
                    </div>
                    <div class="form-group">
                        <label for="talla_l">Cantidad Talla L (Máx: <span id="talla_l_limit"></span>):</label>
                        <input type="number" class="form-control" name="talla_l" min="0" id="talla_l_s" >
                    </div>
                    <div class="form-group">
                        <label for="talla_xl">Cantidad Talla XL (Máx: <span id="talla_xl_limit"></span>):</label>
                        <input type="number" class="form-control" name="talla_xl" min="0" id="talla_xl_s" >
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Guardar</button>
                </form>
            </div>
        </div>
    </div>
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
                        <input type="number" class="form-control" name="talla_s" min="0">
                    </div>
                    <div class="form-group">
                        <label for="talla_m">Cantidad Talla M:</label>
                        <input type="number" class="form-control" name="talla_m" min="0">
                    </div>
                    <div class="form-group">
                        <label for="talla_l">Cantidad Talla L:</label>
                        <input type="number" class="form-control" name="talla_l" min="0">
                    </div>
                    <div class="form-group">
                        <label for="talla_xl">Cantidad Talla XL:</label>
                        <input type="number" class="form-control" name="talla_xl" min="0">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>




<script>

// Variables globales para los límites y valores iniciales
let talla_s_max, talla_m_max, talla_l_max, talla_xl_max;
let inicioS, inicioM, inicioL, inicioXL;

    $('#createSequenceModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var opId = button.data('op-id');
        var fechaInicioProduccion = button.data('fecha-inicio');
        var fechaFinalProduccion = button.data('fecha-entrega');
        
        // Establece el ID de la OP en el formulario
        $('#opIdInput').val(opId);
        
        // Configura los límites de los campos de fecha
        $('#fechaInicio').attr('min', fechaInicioProduccion);
        $('#fechaInicio').attr('max', fechaFinalProduccion);
        $('#fechaFinal').attr('min', fechaInicioProduccion);
        $('#fechaFinal').attr('max', fechaFinalProduccion);

        
       // Datos de producción
        talla_s_max = parseInt(button.data('talla_s'));
        talla_m_max = parseInt(button.data('talla_m'));
        talla_l_max = parseInt(button.data('talla_l'));
        talla_xl_max = parseInt(button.data('talla_xl'));

        // Cantidades ya registradas en secuencias anteriores
        inicioS = parseInt(button.data('talla_s_registrada')) || 0;
        inicioM = parseInt(button.data('talla_m_registrada')) || 0;
        inicioL = parseInt(button.data('talla_l_registrada')) || 0;
        inicioXL = parseInt(button.data('talla_xl_registrada')) || 0;

        // Configurar el máximo en el frontend
        $('#talla_s_limit').text(talla_s_max - inicioS);
        $('#talla_m_limit').text(talla_m_max - inicioM);
        $('#talla_l_limit').text(talla_l_max - inicioL);
        $('#talla_xl_limit').text(talla_xl_max - inicioXL);
    });

    $('#formCreateSequence').on('submit', function(e) {
        const cantidadS = parseInt($('#talla_s_s').val()) || 0;
        const cantidadM = parseInt($('#talla_m_s').val()) || 0;
        const cantidadL = parseInt($('#talla_l_s').val()) || 0;
        const cantidadXL = parseInt($('#talla_xl_s').val()) || 0;

        // Validar que al menos un campo tenga un valor mayor que 0
        if (cantidadS === 0 && cantidadM === 0 && cantidadL === 0 && cantidadXL === 0) {
            alert('Debe ingresar al menos un valor en uno de los campos de cantidad.');
            return false;
        }

        // Validar que la cantidad total registrada no sobrepase el máximo permitido
        if ((inicioS + cantidadS) > talla_s_max) {
            alert('La cantidad de prendas para la talla S supera el total permitido para la producción.');
            return false;
        } else if ((inicioM + cantidadM) > talla_m_max) {
            alert('La cantidad de prendas para la talla M supera el total permitido para la producción.');
            return false;
        } else if ((inicioL + cantidadL) > talla_l_max) {
            alert('La cantidad de prendas para la talla L supera el total permitido para la producción.');
            return false;
        } else if ((inicioXL + cantidadXL) > talla_xl_max) {
            alert('La cantidad de prendas para la talla XL supera el total permitido para la producción.');
            return false;
        }

        return true;
    });




    $('#fechaInicio, #fechaFinal').on('change', function () {
        var fechaInicio = new Date($('#fechaInicio').val());
        var fechaFinal = new Date($('#fechaFinal').val());

        // Verifica si la fecha final es menor o igual a la fecha de inicio
        if (fechaFinal <= fechaInicio) {
            // Muestra una alerta o deshabilita el botón de envío
            alert("La fecha final debe ser mayor a la fecha de inicio.");
            $('#fechaFinal').val(''); // Opcional: Limpia la fecha final
        }
    });




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


    
    document.getElementById("searchInput").addEventListener("input", function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll(".action-row");

        rows.forEach(row => {
            const opValue = row.getAttribute("data-op").toLowerCase();
            if (opValue.includes(searchValue)) {
                row.style.display = "";
                row.nextElementSibling.style.display = "none"; // Ocultar detalles por defecto
            } else {
                row.style.display = "none";
            }
        });
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

    function toggleDetails(button) {
        const row = button.closest('tr').nextElementSibling;
        row.style.display = row.style.display === 'none' ? '' : 'none';
        button.textContent = row.style.display === 'none' ? '▶' : '▼';
    }

    

</script>

<?php require_once '../../footer.php'; ?>

</body>
</html>
