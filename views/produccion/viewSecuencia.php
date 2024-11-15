<?php
require_once '../../contenido.php';
$idop = isset($_GET['id']) ? intval($_GET['id']) : 0;

$conexion = (new Conexion())->getConexion();
$querySecuencias = "SELECT * FROM secuencias WHERE id = ?";
$stmtSecuencias = $conexion->prepare($querySecuencias);
$stmtSecuencias->execute([$idop]);
$secuencia = $stmtSecuencias->fetch(PDO::FETCH_ASSOC);

$queryTallas = "SELECT * FROM tallas WHERE secuencia_id = ?";
$stmtTallas = $conexion->prepare($queryTallas);
$stmtTallas->execute([$idop]);
$tallas = $stmtTallas->fetch(PDO::FETCH_ASSOC);

$fechaInicio = $secuencia['fechaInicio'];
$fechaFinal = $secuencia['fechaFinal'];

$tallaMaxima = [
    'S' => $tallas['talla_s'],
    'M' => $tallas['talla_m'],
    'L' => $tallas['talla_l'],
    'XL' => $tallas['talla_xl']
];

date_default_timezone_set('America/Lima');
$date = date('Y-m-d');
?>

<div class="container mt-5">
    <h1 class="mb-4" style="text-align: center;">TALLAS</h1>

    <table class="table table-hover" id="actionsTable">
        <thead> 
            <tr>
                <th>Talla</th>
                <th>Cantidad</th> 
                <th>Realizadas</th>
                <th>Faltantes</th>
                <th>Kardex</th>
                <th>Historial</th>
            </tr>
        </thead>
        <tbody>
    <?php if (!empty($tallas)): ?>
        <?php 
        $tallasArray = ['S', 'M', 'L', 'XL'];
        foreach ($tallasArray as $talla): 
            $cantidad = $tallas['talla_' . strtolower($talla)];
            $realizadas = $tallas['realizadas_' . strtolower($talla)] ?? 0;
            $faltantes = $cantidad - $realizadas;
        ?>
        <tr>
            <td><?= $talla ?></td>
            <td><?= htmlspecialchars($cantidad) ?></td>
            <td><?= htmlspecialchars($realizadas) ?></td>
            <td><?= htmlspecialchars($faltantes) ?></td>
            <td>
                <button class="btn btn-info btn-sm <?= $cantidad == 0 ? 'disabled' : '' ?>" onclick="mostrarKardex('<?= $talla ?>')">Kardex</button>
            </td>
            <td>
                <button class="btn btn-warning btn-sm <?= $cantidad == 0 ? 'disabled' : '' ?>" onclick="mostrarHistorial('<?= $talla ?>')">Historial</button>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" style="text-align: center;">No hay tallas registradas.</td>
        </tr>
    <?php endif; ?>
    </tbody>
    </table>
               
    <a href="<?= $host ?>/views/produccion/indexP.php" class="btn btn-secondary">Regresar</a>
</div>

<!-- Modal para Kardex -->
<div class="modal fade" id="kardexModal" tabindex="-1" aria-labelledby="kardexModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kardexModalLabel">Registrar Kardex - Talla: <span id="kardexTalla"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="kardexForm">
                    <div class="mb-3">
                        <label for="kardexFecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="kardexFecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="kardexCantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="kardexCantidad" min="1" required>
                    </div>
                    <input type="hidden" id="kardexTallaId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarKardex()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Historial -->
<div class="modal fade" id="historialModal" tabindex="-1" aria-labelledby="historialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historialModalLabel">Historial - Talla: <span id="historialTalla"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody id="historialBody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    const fechaInicio = "<?= $fechaInicio ?>";
    const fechaFinal = "<?= $date ?>";

    const tallaMaxima = {
        S: <?= $tallaMaxima['S'] ?>,
        M: <?= $tallaMaxima['M'] ?>,
        L: <?= $tallaMaxima['L'] ?>,
        XL: <?= $tallaMaxima['XL'] ?>
    };


function mostrarKardex(talla, tallaId) {
    document.getElementById('kardexTalla').innerText = talla;
    document.getElementById('kardexTallaId').value = tallaId;

    const fechaInput = document.getElementById('kardexFecha');
    fechaInput.min = fechaInicio;
    fechaInput.max = fechaFinal;

    $('#kardexModal').modal('show');
}

function guardarKardex() {
    const tallaId = <?= htmlspecialchars($tallas['id']) ?> 
    const fecha = document.getElementById('kardexFecha').value;
    const cantidad = document.getElementById('kardexCantidad').value;
    const talla = document.getElementById('kardexTalla').innerText;

    if (cantidad <= 0) {
        alert('La cantidad debe ser mayor a 0.');
        return;
    }
    
    if (parseInt(cantidad, 10) + parseInt(getRealizadas(talla), 10) > tallaMaxima[talla]) {
        alert(`La cantidad para la talla ${talla} no puede superar el límite.`);
        return;
    }

    
    if (fecha && cantidad) {
        $.ajax({
            url: '../../controllers/produccion/kardexController.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                talla_id: tallaId,
                fecha: fecha,
                cantidad: cantidad,
                talla: talla
            }),
            success: function(response) {
                alert('Movimiento registrado en el Kardex.');
                $('#kardexModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('Error al registrar el movimiento en el Kardex.');
            }
        });
    } else {
        alert('Por favor, completa todos los campos.');
    }
}

function getRealizadas(talla) {
    switch (talla) {
        case 'S': return <?= htmlspecialchars($tallas['realizadas_s'] ?? 0) ?>;
        case 'M': return <?= htmlspecialchars($tallas['realizadas_m'] ?? 0) ?>;
        case 'L': return <?= htmlspecialchars($tallas['realizadas_l'] ?? 0) ?>;
        case 'XL': return <?= htmlspecialchars($tallas['realizadas_xl'] ?? 0) ?>;
        default: return 0;
    }
}


function mostrarHistorial(talla, secuenciaId) {
    document.getElementById('historialTalla').innerText = talla;

    $.ajax({
        url: '../../controllers/produccion/historialKardex.php',
        type: 'GET',
        data: { talla: talla, secuencia_id:  <?= htmlspecialchars($tallas['id']) ?> },
        success: function(response) {
            let historialHTML = '';
            const historial = JSON.parse(response);

            if (historial.length > 0) {
                historial.forEach(function(item) {
                    historialHTML += `
                        <tr>
                            <td>${item.fecha}</td>
                            <td>${item.cantidad}</td>
                        </tr>
                    `;
                });
            } else {
                historialHTML = `<tr><td colspan="2">No hay historial para esta talla.</td></tr>`;
            }

            document.getElementById('historialBody').innerHTML = historialHTML;
            $('#historialModal').modal('show');
        },
        error: function(xhr, status, error) {
            alert('Error al cargar el historial.');
        }
    });
}

</script>


<?php require_once '../../footer.php'; ?>
