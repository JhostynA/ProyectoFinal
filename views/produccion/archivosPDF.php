<?php 
require_once '../../contenido.php'; 
require_once '../../models/Conexion.php';

$conexion = new Conexion();
$conn = $conexion->getConexion(); 

$idop = $actionP['idop'];
$stmt = $conn->prepare("SELECT * FROM pdf_files WHERE idop = :idop");
$stmt->bindParam(':idop', $idop);
$stmt->execute();
$pdfs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h1 class="mb-4 text-center" style="color: #000000;">Archivos PDF de la OP - <?= htmlspecialchars($actionP['op']) ?></h1>

    

    <div class="card shadow-sm mb-5 border-danger">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title m-0">Subir Nuevo PDF</h3>
        </div>
        <div class="card-body">
            <form action="upload_pdf.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="pdfFile" class="form-label" style="color: black;">Seleccionar archivo PDF:</label>
                    <input type="file" class="form-control" id="pdfFile" name="pdfFile" accept="application/pdf" required>
                </div>
                <input type="hidden" name="idop" value="<?= $actionP['idop']?>"> 
                <button type="submit" class="btn btn-danger mt-3 w-100">Subir PDF</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-light">
        <div class="card-header bg-light">
            <h3 class="card-title m-0" style="color: #000000;">Archivos PDF Subidos</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($pdfs)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($pdfs as $pdf): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?= $pdf['file_path'] ?>" target="_blank" style="color: black; text-decoration: underline;">
                                <?= basename($pdf['file_name']) ?>
                            </a>
                            <span class="badge bg-danger text-white rounded-pill">PDF</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted mt-3 text-center" style="color: black;">No hay archivos PDF.</p>
            <?php endif; ?>
        </div>
    </div>
<br>
    <div class="text-rigth mb-4">
        <a href="<?= $host ?>/views/produccion/indexP.php?cliente_id=<?= htmlspecialchars($actionP['idcliente']) ?>" class="btn btn-outline-danger btn-lg">⟵ Regresar</a>
    </div>

</div>

<?php require_once '../../footer.php'; ?>
</body>
</html>
