<?php require_once '../../contenido.php'; 
$idop = isset($_GET['id']) ? intval($_GET['id']) : 0;
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
                <form method="POST" action="?action=createSequence"> <!-- Cambia la acción aquí -->
                    <input type="hidden" name="idop" value="<?= $idop ?>"> <!-- Campo oculto para idop -->
                    <div class="form-group">
                        <label for="numSecuencia">Número de Secuencia:</label>
                        <input type="number" class="form-control" name="numSecuencia" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaInicio">Fecha de Inicio:</label>
                        <input type="date" class="form-control" name="fechaInicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaFinal">Fecha Final:</label>
                        <input type="date" class="form-control" name="fechaFinal" required>
                    </div>
                    <div class="form-group">
                        <label for="prendasArealizar">Prendas a Realizar:</label>
                        <input type="number" class="form-control" name="prendasArealizar" required>
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




    <?php require_once '../../footer.php'; ?>

</body>
</html>