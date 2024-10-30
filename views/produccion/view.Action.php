<?php require_once '../../contenido.php'; 
$idop = isset($_GET['id']) ? intval($_GET['id']) : 0;

$conexion = (new Conexion())->getConexion();
$queryProduccion = "SELECT fecha_inicio, fecha_entrega, talla_s, talla_m, talla_l, talla_xl FROM actions WHERE id = ?";
$stmt = $conexion->prepare($queryProduccion);
$stmt->execute([$idop]);
$produccion = $stmt->fetch(PDO::FETCH_ASSOC);

$fechaInicioProduccion = $produccion['fecha_inicio'];
$fechaFinalProduccion = $produccion['fecha_entrega']; 
$cantidadTotalTS = $produccion['talla_s'];
$cantidadTotalTM = $produccion['talla_m'];
$cantidadTotalTL = $produccion['talla_l'];
$cantidadTotalTXL = $produccion['talla_xl'];

$querySecuencias = "SELECT * FROM secuencias WHERE idop = ?";
$stmtSecuencias = $conexion->prepare($querySecuencias);
$stmtSecuencias->execute([$idop]);
$secuencias = $stmtSecuencias->fetchAll(PDO::FETCH_ASSOC);

$queryPrendasRealizadas = "SELECT SUM(talla_s) as total_talla_s, SUM(talla_m) as total_talla_m, SUM(talla_l) as total_talla_l, SUM(talla_xl) as total_talla_xl FROM secuencias WHERE idop = ?";
$stmtPrendasRealizadas = $conexion->prepare($queryPrendasRealizadas);
$stmtPrendasRealizadas->execute([$idop]);
$prendasRealizadas = $stmtPrendasRealizadas->fetch(PDO::FETCH_ASSOC);
$prendasRealizadasS = intval($prendasRealizadas['total_talla_s']);
$prendasRealizadasM = intval($prendasRealizadas['total_talla_m']);
$prendasRealizadasL = intval($prendasRealizadas['total_talla_l']);
$prendasRealizadasXL = intval($prendasRealizadas['total_talla_xl']);

?>

<div class="container mt-5">

    <!-- <h1 class="mb-4" style="text-align: center;">SECUENCIAS - OP <?= htmlspecialchars($produccion['nombre']) ?></h1> -->
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
            <form method="POST" action="?action=createSequence" onsubmit="return validarFechas() && validarTallas();">
                    <input type="hidden" name="idop" value="<?= $idop ?>"> 
                    <div class="form-group">
                        <label for="numSecuencia">Número de Secuencia:</label>
                        <input type="number" class="form-control" name="numSecuencia" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaInicio">Fecha de Inicio:</label>
                        <input type="date" class="form-control" name="fechaInicio" id="fechaInicio" 
                            min="<?= htmlspecialchars($fechaInicioProduccion); ?>" 
                            max="<?= htmlspecialchars($fechaFinalProduccion); ?>" 
                            required>
                    </div>
                    <div class="form-group">
                        <label for="fechaFinal">Fecha Final:</label>
                        <input type="date" class="form-control" name="fechaFinal" id="fechaFinal" 
                               min="<?= htmlspecialchars($fechaInicioProduccion); ?>" 
                               max="<?= htmlspecialchars($fechaFinalProduccion); ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" value="s" onchange="toggleQuantityInput(this)"> Talla S
                        <input type="number" class="form-control" id="cantidadS" name="talla_s" min="0" disabled>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" value="m" onchange="toggleQuantityInput(this)"> Talla M
                        <input type="number" class="form-control" id="cantidadM" name="talla_m" min="0" disabled>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" value="l" onchange="toggleQuantityInput(this)"> Talla L
                        <input type="number" class="form-control" id="cantidadL" name="talla_l" min="0" disabled>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" value="xl" onchange="toggleQuantityInput(this)"> Talla XL
                        <input type="number" class="form-control" id="cantidadXL" name="talla_xl" min="0" disabled>
                    </div>


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
    function toggleQuantityInput(checkbox) {
        const talla = checkbox.value;
        const cantidadInput = document.getElementById('cantidad' + talla.toUpperCase());
        cantidadInput.disabled = !checkbox.checked;
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

    const cantidadTotalTS = <?= $cantidadTotalTS ?>;
    const cantidadTotalTM = <?= $cantidadTotalTM ?>;
    const cantidadTotalTL = <?= $cantidadTotalTL ?>;
    const cantidadTotalTXL = <?= $cantidadTotalTXL ?>;

    const prendasRealizadasS = <?= $prendasRealizadasS ?>;
    const prendasRealizadasM = <?= $prendasRealizadasM ?>;
    const prendasRealizadasL = <?= $prendasRealizadasL ?>;
    const prendasRealizadasXL = <?= $prendasRealizadasXL ?>;

    function validarTallas() {
    const cantidadS = parseInt(document.getElementById('cantidadS').value) || 0;
    const cantidadM = parseInt(document.getElementById('cantidadM').value) || 0;
    const cantidadL = parseInt(document.getElementById('cantidadL').value) || 0;
    const cantidadXL = parseInt(document.getElementById('cantidadXL').value) || 0;

    // Verifica que al menos un campo de talla tenga una cantidad mayor a cero
    if (cantidadS === 0 && cantidadM === 0 && cantidadL === 0 && cantidadXL === 0) {
        alert('Debe ingresar una cantidad para al menos una talla.');
        return false;
    }
    
    // Validaciones de cantidad máxima
    if ((prendasRealizadasS + cantidadS) > cantidadTotalTS) {
        alert('La cantidad de prendas para la talla S supera el total permitido para la producción.');
        return false;
    }
    if ((prendasRealizadasM + cantidadM) > cantidadTotalTM) {
        alert('La cantidad de prendas para la talla M supera el total permitido para la producción.');
        return false;
    }
    if ((prendasRealizadasL + cantidadL) > cantidadTotalTL) {
        alert('La cantidad de prendas para la talla L supera el total permitido para la producción.');
        return false;
    }
    if ((prendasRealizadasXL + cantidadXL) > cantidadTotalTXL) {
        alert('La cantidad de prendas para la talla XL supera el total permitido para la producción.');
        return false;
    }

    return true;
}




</script>

<?php require_once '../../footer.php'; ?>

</body>
</html>
