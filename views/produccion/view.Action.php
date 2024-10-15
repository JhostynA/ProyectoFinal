<?php require_once '../../contenido.php'; 
$idop = isset($_GET['id']) ? intval($_GET['id']) : 0;

$conexion = (new Conexion())->getConexion();
$idProduccion = $_GET['id'];
$queryProduccion = "SELECT fecha_inicio, fecha_entrega FROM actions WHERE id = ?";
$stmt = $conexion->prepare($queryProduccion);
$stmt->execute([$idProduccion]);
$produccion = $stmt->fetch(PDO::FETCH_ASSOC);

$fechaInicioProduccion = $produccion['fecha_inicio'];
$fechaFinalProduccion = $produccion['fecha_entrega'];
?>

<div class="container mt-5">
    <h1 class="mb-4" style="text-align: center;">SECUENCIAS</h1>

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
                <form method="POST" action="?action=createSequence" onsubmit="return validarFechas();"> 
                    <input type="hidden" name="idop" value="<?= $idop ?>"> 
                    <div class="form-group">
                        <label for="numSecuencia">Número de Secuencia:</label>
                        <input type="number" class="form-control" name="numSecuencia" required>
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
                            <input type="number" class="form-control mt-2" name="cantidad[<?= $talla ?>]" placeholder="Cantidad" disabled id="cantidad<?= $talla ?>" oninput="updatePrendasArealizar()">
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

    $('#createSequenceModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset(); 
    });

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

        document.getElementById('prendasArealizar').value = total; 
    }

    function validarFechas() {
        const fechaInicio = new Date(document.getElementById('fechaInicio').value);
        const fechaFinal = new Date(document.getElementById('fechaFinal').value);
        
        if (fechaFinal <= fechaInicio) {
            alert('La fecha final debe ser mayor que la fecha de inicio.');
            return false; // Evita el envío del formulario
        }
        return true; // Permite el envío del formulario
    }
</script>

<?php require_once '../../footer.php'; ?>

</body>
</html>
