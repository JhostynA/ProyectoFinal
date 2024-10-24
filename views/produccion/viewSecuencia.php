
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
            <?php foreach ($tallas as $talla): ?>
                <tr>
                    <td><?= htmlspecialchars($talla['talla']) ?></td>
                    <td><?= htmlspecialchars($talla['cantidad']) ?></td> 
                    <td><?= htmlspecialchars($talla['realizadas']) ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="mostrarKardex(<?= htmlspecialchars($talla['id']) ?>)">Kardex</button>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="mostrarHistorial(<?= htmlspecialchars($talla['id']) ?>)">Historial</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
               
    <a href="<?= $host ?>/views/produccion/indexP.php?action=view&id=<?= $secuencia['idop'] ?>" class="btn btn-secondary">Regresar a Secuencias</a>

</div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function mostrarKardex(tallaId) {
           
           
        }

        function mostrarHistorial(tallaId) {      
            
            
        }

    </script>

    <?php require_once '../../footer.php'; ?>

</body>

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
            <?php foreach ($tallas as $talla): ?>
                <tr>
                    <td><?= htmlspecialchars($talla['talla']) ?></td>
                    <td><?= htmlspecialchars($talla['cantidad']) ?></td> 
                    <td>0</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="mostrarKardex(<?= htmlspecialchars($talla['id']) ?>)">Kardex</button>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="mostrarHistorial(<?= htmlspecialchars($talla['id']) ?>)">Historial</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
               
    <a href="<?= $host ?>/views/produccion/indexP.php?action=view&id=<?= $secuencia['idop'] ?>" class="btn btn-secondary">Regresar a Secuencias</a>
</div>




    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function mostrarKardex(tallaId) {
           
           
        }

        function mostrarHistorial(tallaId) {      
            
            
        }

    </script>

    <?php require_once '../../footer.php'; ?>

</body>
</html>