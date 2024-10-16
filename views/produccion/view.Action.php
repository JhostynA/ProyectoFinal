<?php require_once '../../contenido.php'; 
$idop = isset($_GET['id']) ? intval($_GET['id']) : 0;

$conexion = (new Conexion())->getConexion();
$queryProduccion = "SELECT fecha_inicio, fecha_entrega, cantidad_prendas FROM actions WHERE id = ?";
$stmt = $conexion->prepare($queryProduccion);
$stmt->execute([$idop]);
$produccion = $stmt->fetch(PDO::FETCH_ASSOC);

$fechaInicioProduccion = $produccion['fecha_inicio'];
$fechaFinalProduccion = $produccion['fecha_entrega'];
$totalPrendasProduccion = $produccion['cantidad_prendas']; 

$querySecuencias = "SELECT COALESCE(SUM(prendasArealizar), 0) AS totalPrendasAsignadas FROM secuencias WHERE idop = ?";
$stmtSecuencias = $conexion->prepare($querySecuencias);
$stmtSecuencias->execute([$idop]);
$secuencia = $stmtSecuencias->fetch(PDO::FETCH_ASSOC);
$totalPrendasAsignadas = $secuencia['totalPrendasAsignadas'];

$querySecuenciasListado = "SELECT * FROM secuencias WHERE idop = ?";
$stmtSecuenciasListado = $conexion->prepare($querySecuenciasListado);
$stmtSecuenciasListado->execute([$idop]);
$secuencias = $stmtSecuenciasListado->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h1 class="mb-4" style="text-align: center;">OP - <?= htmlspecialchars($action['nombre']) ?></h1>

    <div class="d-flex justify-content-between mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createSequenceModal">
            Nueva Secuencia
        </button>
    </div>

    <table class="table table-hover" id="actionsTable">
        <thead>
        <tr>
            <th>Secuencia</th>
            <th>Fecha inicio</th>
            <th>Fecha final</th>
            <th>Prendas a realizar</th>
            <th>Prendas faltantes</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($secuencias as $secuencia): ?>
                <tr>
                    <td><a href="<?= $host ?>/views/produccion/indexP.php?action=viewSecuencia&id=<?= $secuencia['id'] ?>" class="text-primary">
                            <?= htmlspecialchars($secuencia['numSecuencia']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($secuencia['fechaInicio']) ?></td>
                    <td><?= htmlspecialchars($secuencia['fechaFinal']) ?></td>
                    <td><?= htmlspecialchars($secuencia['prendasArealizar']) ?></td>
                    <td><?= htmlspecialchars($secuencia['prendasFaltantes']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
               
    <a href="indexP.php" class="btn btn-secondary mt-3">Regresar</a>
</div>

<!-- Modal para registrar una nueva secuencia -->
<div class="modal fade" id="createSequenceModal" tabindex="-1" role="dialog" aria-labelledby="createSequenceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSequenceModalLabel">Registrar Nueva Secuencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="?action=createSequence" onsubmit="return validarFechas() && validarPrendasArealizar();">
                    <input type="hidden" name="idop" value="<?= $idop ?>"> 
                    <div class="form-group">
                        <label for="numSecuencia">Número de Secuencia:</label>
                        <input type="number" class="form-control" name="numSecuencia" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaInicio">Fecha de Inicio:</label>
                        <input type="date" class="form-control" name="fechaInicio" id="fechaInicio" 
                            min="<?php echo ($fechaInicioProduccion); ?>" 
                            max="<?php echo ($fechaFinalProduccion); ?>" 
                            required>
                    </div>
                    <div class="form-group">
                        <label for="fechaFinal">Fecha Final:</label>
                        <input type="date" class="form-control" name="fechaFinal" id="fechaFinal" 
                               min="<?php echo $fechaInicioProduccion; ?>" 
                               max="<?php echo $fechaFinalProduccion; ?>" 
                               required>
                    </div>
                    <div class="form-group" style="display: none;">
                        <input type="number" class="form-control" name="prendasArealizar" id="prendasArealizar" required readonly>
                    </div>
                    <label>Tallas:</label>
                    <?php
                    $tallasDisponibles = ['S', 'M', 'L', 'XL']; 
                    foreach ($tallasDisponibles as $talla): ?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="tallas[]" value="<?= $talla ?>" id="talla<?= $talla ?>" onchange="toggleQuantityInput(this)">
                            <label class="form-check-label" for="talla<?= $talla ?>"><?= $talla ?></label>
                            <input type="number" class="form-control mt-2" name="cantidad[<?= $talla ?>]" placeholder="Cantidad" min="1" disabled id="cantidad<?= $talla ?>" oninput="updatePrendasArealizar()">
                        </div>
                    <?php endforeach; ?>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('error') && urlParams.get('error') === 'NumSecuenciaDuplicado') {
            alert('Ya existe una secuencia con ese número.');

            // Limpiar la URL eliminando los parámetros
            const newUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
    });

    $('#createSequenceModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset(); // Limpiar los campos del formulario
    });
    
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fechaInicioProduccion = "<?= $fechaInicioProduccion; ?>";
        const fechaFinalProduccion = "<?= $fechaFinalProduccion; ?>";
        const fechaInicioInput = document.querySelector('input[name="fechaInicio"]');
        const fechaEntregaInput = document.querySelector('input[name="fechaFinal"]');

        [fechaInicioInput, fechaEntregaInput].forEach(input => {
            input.setAttribute('min', fechaInicioProduccion);
            input.setAttribute('max', fechaFinalProduccion);
        });

        fechaInicioInput.addEventListener('change', function() {
            fechaEntregaInput.setAttribute('min', this.value);
            if (new Date(fechaEntregaInput.value) < new Date(this.value)) {
                fechaEntregaInput.value = '';
            }
        });
    });

    let totalPrendasAsignadas = <?= $totalPrendasAsignadas; ?>; 
    let totalPrendasProduccion = <?= $totalPrendasProduccion; ?>; 

    function validarPrendasArealizar() {
        const prendasNuevaSecuencia = parseInt(document.getElementById('prendasArealizar').value) || 0;
        const sumaTotalPrendas = totalPrendasAsignadas + prendasNuevaSecuencia;

        if (sumaTotalPrendas > totalPrendasProduccion) {
            alert('La cantidad total de prendas a realizar: ' + sumaTotalPrendas + ' supera las prendas de la producción: ' + totalPrendasProduccion + '.');
            return false; 
        }

        return true; 
    }

    function toggleQuantityInput(checkbox) {
        const talla = checkbox.value;
        const cantidadInput = document.getElementById('cantidad' + talla);
        cantidadInput.disabled = !checkbox.checked;

        if (!checkbox.checked) {
            cantidadInput.value = 0; 
            updatePrendasArealizar();
        }
    }

    function updatePrendasArealizar() {
        const tallasDisponibles = ['S', 'M', 'L', 'XL'];
        let total = 0;

        tallasDisponibles.forEach(talla => {
            const cantidadInput = document.getElementById('cantidad' + talla);
            if (!cantidadInput.disabled) {
                total += parseInt(cantidadInput.value) || 0; 
            }
        });

        if (total > totalPrendasProduccion) {
            alert('La cantidad total de prendas a realizar no puede superar las prendas de la producción (' + totalPrendasProduccion + ').');
            return false;
        }

        document.getElementById('prendasArealizar').value = total; 
        return true;
    }

    function validarFechas() {
        const fechaInicio = new Date(document.getElementById('fechaInicio').value);
        const fechaFinal = new Date(document.getElementById('fechaFinal').value);
        
        if (fechaFinal <= fechaInicio) {
            alert('La fecha final debe ser mayor que la fecha de inicio.');
            return false; 
        }
        return true; 
    } 

    

</script>

<?php require_once '../../footer.php'; ?>

</body>
</html>