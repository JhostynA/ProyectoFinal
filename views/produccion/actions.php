<?php require_once '../../contenido.php'; 
require_once '../../models/produccion/ActionModel.php';
$secuenciasModel = new ActionModel();

$tallas = $secuenciasModel->getTallas();


?>

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <h1 class="mb-4 text-center">PRODUCCIÓN</h1>
    </div>

    <table id="actionsTable" class="table table-bordered shadow-lg">
        <thead class="thead-dark">
            <tr>
                <th class="text-center" style="width: 80px;">OP</th>
                <th style="width: 80px;"  class="text-center">DOP</th>
                <th style="width: 120px;" class="text-center">Estilo</th>
                <th style="width: 120px;" class="text-center">División</th>
                <th style="width: 120px;" class="text-center">Color</th>
                <th style="width: 120px;" class="text-center">Fecha Inicio</th>
                <th style="width: 120px;" class="text-center">Fecha Entrega</th>
                <th style="width: 120px;" class="text-center">PDF</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($actions) && !empty($actions)): ?>
                <?php foreach ($actions as $action): ?>
                    <tr class="table-hover action-row" data-op="<?= htmlspecialchars($action['idop']) ?>">
                        <td class="text-center"><?= htmlspecialchars($action['op']) ?></td>
                        <td class="text-center"><button class="btn btn-link" onclick="toggleDetails(this)">▶</button></td>
                        <td><?= htmlspecialchars($action['estilo']) ?></td>
                        <td><?= htmlspecialchars($action['division']) ?></td>
                        <td><?= htmlspecialchars($action['color']) ?></td>
                        <td><?= htmlspecialchars($action['fechainicio']) ?></td>
                        <td><?= htmlspecialchars($action['fechafin']) ?></td>
                        <td class="text-center">
                        <a href="<?= $host ?>/views/produccion/indexP.php?action=viewPDF&id=<?= $action['idop'] ?>" class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf"></i>
                            </a></td>
                    </tr>
                    <tr class="details" style="display: none; background-color: #f9f9f9;">
                    <td colspan="10">
                        <div class="d-flex align-items-center">
                        <button class="btn btn-primary btn-sm mr-3 open-modal-btn" 
                            data-toggle="modal" 
                            data-target="#createSequenceModal" 
                            data-op="<?= htmlspecialchars($action['idop']) ?>">
                        Nuevo Detalle Producción
                    </button>


                        </div>

                        <table class="table table-sm rounded shadow-sm mt-3">
                            <thead class="thead-light" style="background-color: #007bff; color: #fff;">
                                <tr>
                                    <th>N. Secuencia</th>
                                    <th>Talla</th>
                                    <th>Cantidad</th>
                                    <th>F Inicio</th>
                                    <th>F Final</th>
                                </tr>
                            </thead>
                           
                            <tbody>
                                <?php
                                $detalleOP = $secuenciasModel->getDetalleByOP($action['idop']);
                                foreach ($detalleOP as $detalleop): ?>
                                    <tr>
                                        <td><a href="<?= $host ?>/views/produccion/indexP.php?action=viewSecuencia&iddetop=<?= $detalleop['iddetop'] ?>" class="text-primary">
                                                <?= htmlspecialchars($detalleop['numSecuencia']) ?>              
                                            </a>
                                        </td>
                                        <td><?= htmlspecialchars($detalleop['talla']) ?></td>
                                        <td><?= htmlspecialchars($detalleop['cantidad']) ?></td>
                                        <td><?= htmlspecialchars($detalleop['sinicio']) ?></td>
                                        <td><?= htmlspecialchars($detalleop['sfin']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($detalleOP)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No hay detalles disponibles para esta OP.</td>
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



<div class="modal fade" id="createSequenceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Detalle de Producción</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCreateSequence" method="POST" action="<?= $host ?>/views/produccion/indexP.php?action=createSequence">
                    <input type="hidden" name="idop" id="opIdInput" value="">
                    <input type="hidden" name="idcliente" id="clienteIdInput" value="<?= htmlspecialchars($action['idcliente']) ?>">

                    <div class="form-group">
                        <label for="numSecuencia">Número de Secuencia:</label>
                        <input type="number" class="form-control" name="numSecuencia" required>
                    </div>

                    <div class="form-group">
                        <label for="idtalla">Talla:</label>
                        <select class="form-control" name="idtalla" id="idtalla" required>
                            <option value="" selected>Seleccione una talla</option>
                            <?php foreach ($tallas as $talla): ?>
                                <option value="<?= htmlspecialchars($talla['idtalla']) ?>">
                                    <?= htmlspecialchars($talla['talla']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" class="form-control" name="cantidad" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="sinicio">Fecha de Inicio:</label>
                        <input type="date" class="form-control" name="sinicio" id="sinicio" required>
                    </div>

                    <div class="form-group">
                        <label for="sfin">Fecha Final:</label>
                        <input type="date" class="form-control" name="sfin" id="sfin" required>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtn">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



<script>
    document.querySelectorAll('.open-modal-btn').forEach(button => {
        button.addEventListener('click', function () {
            const opId = this.getAttribute('data-op');
            document.getElementById('opIdInput').value = opId;
        });
    });

    function toggleDetails(button) {
        const row = button.closest('tr').nextElementSibling;
        row.style.display = row.style.display === 'none' ? '' : 'none';
        button.textContent = row.style.display === 'none' ? '▶' : '▼';
    }

    
</script>

<script>
    const fechainicioProduccion = "<?= htmlspecialchars($action['fechainicio']) ?>"; 
    const fechafinProduccion = "<?= htmlspecialchars($action['fechafin']) ?>";    

    const fechaInicioInput = document.querySelector('input[name="sinicio"]');
    const fechaFinInput = document.querySelector('input[name="sfin"]');

    fechaInicioInput.setAttribute('min', fechainicioProduccion);
    fechaInicioInput.setAttribute('max', fechafinProduccion);

    fechaInicioInput.addEventListener('change', function () {
        const selectedFechaInicio = new Date(this.value);

        if (!isNaN(selectedFechaInicio.getTime())) {
            const fechaMinimaEntrega = selectedFechaInicio.toISOString().split('T')[0];
            fechaFinInput.setAttribute('min', fechaMinimaEntrega);
            fechaFinInput.setAttribute('max', fechafinProduccion);

            const selectedFechaFin = new Date(fechaFinInput.value);
            if (selectedFechaFin < selectedFechaInicio || selectedFechaFin > new Date(fechafinProduccion)) {
                fechaFinInput.value = '';
            }
        }
    });
</script>



<?php require_once '../../footer.php'; ?>

</body>
</html>
