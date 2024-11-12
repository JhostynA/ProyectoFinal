<?php
require_once '../../contenido.php';
require_once '../../models/produccion/ActionModel.php';
$clientes = (new ActionModel())->getClientesActivos();
?>

<div class="container my-5 p-4 shadow-sm rounded bg-light">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Registrar Nueva Producción</h2>
        <a href="<?= $host ?>/views/produccion/registrarClientes.php" class="btn btn-secondary">Administrar Clientes</a>
    </div>
    
    <form id="formCreateAction" method="POST" action="<?= $host ?>/views/produccion/indexP.php?action=create">
        
        <!-- Primera Fila: Cliente, OP, División -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="idcliente" class="form-label">Cliente:</label>
                <select class="form-select" id="idcliente" name="idcliente" required>
                    <option value="">Seleccione un cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombrecliente']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="nombre" class="form-label">OP:</label>
                <input type="number" class="form-control" name="nombre" required>
            </div>
            <div class="col-md-4">
                <label for="division" class="form-label">División:</label>
                <input type="text" class="form-control" id="division" name="division" required>
            </div>
        </div>
        
        <!-- Segunda Fila: Estilo, Color, Fecha de Inicio, Fecha de Entrega -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="estilo" class="form-label">Estilo:</label>
                <input type="text" class="form-control" id="estilo" name="estilo" required>
            </div>
            <div class="col-md-3">
                <label for="color" class="form-label">Color:</label>
                <input type="text" class="form-control" id="color" name="color" required>
            </div>
            <div class="col-md-3">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="col-md-3">
                <label for="fecha_entrega" class="form-label">Fecha de Entrega:</label>
                <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" required>
            </div>
        </div>
        
        <!-- Tercera Fila: Cantidades por Tallas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="talla_s" class="form-label">Cantidad Talla S:</label>
                <input type="number" class="form-control" id="talla_s" name="talla_s" min="0" >
            </div>
            <div class="col-md-3">
                <label for="talla_m" class="form-label">Cantidad Talla M:</label>
                <input type="number" class="form-control" id="talla_m" name="talla_m" min="0" >
            </div>
            <div class="col-md-3">
                <label for="talla_l" class="form-label">Cantidad Talla L:</label>
                <input type="number" class="form-control" id="talla_l" name="talla_l" min="0" >
            </div>
            <div class="col-md-3">
                <label for="talla_xl" class="form-label">Cantidad Talla XL:</label>
                <input type="number" class="form-control" id="talla_xl" name="talla_xl" min="0" >
            </div>
        </div>
        
        <!-- Botón de Envío -->
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Registrar Producción</button>
        </div>
    </form>
</div>


<script>
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

        const opInput = document.querySelector('input[name="nombre"]');
        const form = opInput.closest('form');
        form.addEventListener('submit', function(event){
            const op = parseInt(opInput.value, 10);
            if(op <= 0){
                event.preventDefault();
                alert('La OP debe ser mayor a 0');
            }
        });

        form.addEventListener('submit', function(event) {
        const op = parseInt(opInput.value, 10);
        if(op <= 0) {
            event.preventDefault();
            alert('La OP debe ser mayor a 0');
            return;
        }

        const tallaS = parseInt(document.getElementById('talla_s').value) || 0;
        const tallaM = parseInt(document.getElementById('talla_m').value) || 0;
        const tallaL = parseInt(document.getElementById('talla_l').value) || 0;
        const tallaXL = parseInt(document.getElementById('talla_xl').value) || 0;
        
        const totalPrendas = tallaS + tallaM + tallaL + tallaXL;
        
        if (totalPrendas <= 0) {
            event.preventDefault();
            alert('La suma de todas las tallas debe ser mayor a 0');
            return;
        }
    });


        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('error') && urlParams.get('error') === 'NombreYaExiste') {
            alert('Ya existe una OP con ese número');
            
            const newUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
    });
</script>

<?php require_once '../../footer.php'; ?>
