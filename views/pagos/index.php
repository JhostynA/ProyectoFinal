<?php require_once '../../contenido.php'; 

require_once '../../controllers/pagos/pagosControllers.php';

$pagosController = new PagosController();
$clientes = $pagosController->getClientesActivos();

?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-0">Pagos</h1>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form>
                <div class="row g-3">
                  <div class="col-md-3">
                    <label for="selectCliente" class="form-label">Cliente</label>
                    <select class="form-select" id="selectCliente" name="selectCliente">
                        <option selected disabled>Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= htmlspecialchars($cliente['idcliente']) ?>">
                                <?= htmlspecialchars($cliente['nombrecomercial']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="selectOP" class="form-label">Orden de Producción (OP)</label>
                    <select class="form-select" id="selectOP" name="selectOP">
                        <option selected>Seleccione una OP</option>
                    </select>
                </div>
                <div class="col-md-3">
                        <label for="selectSecuencia" class="form-label">Secuencia</label>
                        <select class="form-select" id="selectSecuencia" name="selectSecuencia">
                            <option selected>Seleccione una secuencia</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="selectTrabajadores" class="form-label">Trabajadores</label>
                        <select class="form-select" id="selectTrabajadores" name="selectTrabajadores">
                            <option selected>Seleccione un trabajador</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Operación</th>
                    <th scope="col">Precio de Operación</th>
                    <th scope="col">Cantidad Realizada</th>
                    <th scope="col">Total Paga</th>
                    <th scope="col">Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2024-11-01</td>
                    <td>Corte</td>
                    <td>$10.00</td>
                    <td>50</td>
                    <td>$500.00</td>
                    <td>
                      <button class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i> Pagar</button>
                    </td>
                </tr>
                <tr>
                    <td>2024-11-02</td>
                    <td>Confección</td>
                    <td>$15.00</td>
                    <td>30</td>
                    <td>$450.00</td>
                    <td>
                        <button class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i> Pagar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script>
    document.getElementById('selectCliente').addEventListener('change', function () {
    const idCliente = this.value;
    const selectOP = document.getElementById('selectOP');

    selectOP.innerHTML = '<option selected>Seleccione una OP</option>';

    if (idCliente) {
        fetch('./clienteAjax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `idcliente=${idCliente}`,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.length > 0) {
                    data.forEach((op) => {
                        const option = document.createElement('option');
                        option.value = op.idop; 
                        option.textContent = op.op; 
                        selectOP.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'No hay OP disponibles';
                    option.disabled = true;
                    selectOP.appendChild(option);
                }
            })
            .catch((error) => console.error('Error al cargar las OP:', error));
    }
});

document.getElementById('selectOP').addEventListener('change', function () {
    const idop = this.value;
    const selectNS = document.getElementById('selectSecuencia');

    selectNS.innerHTML = '<option selected>Seleccione una secuencia</option>';

    if (idop) {
        fetch('./numSecuencia.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `idop=${idop}`,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.length > 0) {
                    data.forEach((ns) => {
                        const option = document.createElement('option');
                        option.value = ns.numSecuencia; 
                        option.textContent = ` ${ns.numSecuencia}`; 
                        selectNS.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'No hay secuencias disponibles';
                    option.disabled = true;
                    selectNS.appendChild(option);
                }
            })
            .catch((error) => console.error('Error al cargar las secuencias:', error));
    }
});

document.getElementById('selectSecuencia').addEventListener('change', function () {
    const iddetop = this.value;
    const selectP = document.getElementById('selectTrabajadores');

    selectP.innerHTML = '<option selected>Seleccione un Trabajador</option>';

    if (iddetop) {
        fetch('./personas.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `iddetop=${iddetop}`,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.length > 0) {
                    data.forEach((trabajador) => {
                        const option = document.createElement('option');
                        option.value = trabajador.idpersona; 
                        option.textContent = trabajador.nombre_completo; 
                        selectP.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'No hay trabajadores disponibles';
                    option.disabled = true;
                    selectP.appendChild(option);
                }
            })
            .catch((error) => console.error('Error al cargar los trabajadores:', error));
    }
});



</script>




<?php require_once '../../footer.php'; ?>

</body>
</html>
