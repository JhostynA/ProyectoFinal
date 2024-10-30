<?php require_once '../../contenido.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4" style="text-align: center;">TALLAS</h1>

    <table class="table table-hover" id="actionsTable">
        <thead>
            <tr>
                <th>Talla</th>
                <th>Cantidad</th> 
                <th>Realizadas</th>
                <th>Kardex</th>
                <th>Historial</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tallas)): ?>
                <tr>
                    <td>S</td>
                    <td><?= htmlspecialchars($tallas['talla_s']) ?></td>
                    <td><?= htmlspecialchars($tallas['realizadas_s'] ?? 0) ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="mostrarKardex('S')">Kardex</button>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="mostrarHistorial('S')">Historial</button>
                    </td>
                </tr>
                <tr>
                    <td>M</td>
                    <td><?= htmlspecialchars($tallas['talla_m']) ?></td>
                    <td><?= htmlspecialchars($tallas['realizadas_m'] ?? 0) ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="mostrarKardex('M')">Kardex</button>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="mostrarHistorial('M')">Historial</button>
                    </td>
                </tr>
                <tr>
                    <td>L</td>
                    <td><?= htmlspecialchars($tallas['talla_l']) ?></td>
                    <td><?= htmlspecialchars($tallas['realizadas_l'] ?? 0) ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="mostrarKardex('L')">Kardex</button>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="mostrarHistorial('L')">Historial</button>
                    </td>
                </tr>
                <tr>
                    <td>XL</td>
                    <td><?= htmlspecialchars($tallas['talla_xl']) ?></td>
                    <td><?= htmlspecialchars($tallas['realizadas_xl'] ?? 0) ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="mostrarKardex('XL')">Kardex</button>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="mostrarHistorial('XL')">Historial</button>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No hay tallas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
               
    <a href="<?= $host ?>/views/produccion/indexP.php?action=view&id=<?= $secuencia['idop'] ?>" class="btn btn-secondary">Regresar a Secuencias</a>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function mostrarKardex(talla) {
        // Implementar lógica para mostrar el kardex de la talla
    }

    function mostrarHistorial(talla) {      
        // Implementar lógica para mostrar el historial de la talla
    }
</script>

<?php require_once '../../footer.php'; ?>
