<?php require_once '../../contenido.php'; ?>

<div class="container mt-5">
        <h1 style="text-align: center;">DETALLES DE PRODUCCIÓN</h1>

        <p><strong>Nombre de producción:</strong> <?= htmlspecialchars($action['nombre']) ?></p> 

        <a href="indexP.php" class="btn btn-secondary mt-3">Back to Actions</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <?php require_once '../../footer.php'; ?>

</body>
</html>