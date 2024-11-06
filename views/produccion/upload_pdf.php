<?php
// Asegúrate de incluir la conexión a la base de datos
require_once '../../models/Conexion.php';

  $conexion = new Conexion();
  $conn = $conexion->getConexion();

// Verificamos si se subió un archivo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['pdfFile'])) {
    // Obtener el ID de la OP (action_id)
    $actionId = $_POST['action_id'];

    // Obtener el archivo PDF
    $pdfFile = $_FILES['pdfFile'];

    // Verificar si hubo algún error en la carga del archivo
    if ($pdfFile['error'] != 0) {
        echo 'Error al cargar el archivo.';
        exit;
    }

    // Validar que el archivo sea PDF
    if ($pdfFile['type'] != 'application/pdf') {
        echo 'El archivo debe ser un PDF.';
        exit;
    }

    // Definir la carpeta donde se guardarán los archivos PDF
    $uploadDir = '../../uploads/pdfs/';
    
    // Verificamos si la carpeta existe, si no, la creamos
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Crear un nombre único para el archivo
    $fileName = uniqid('pdf_') . '_' . basename($pdfFile['name']);
    $filePath = $uploadDir . $fileName;

    // Mover el archivo subido a la carpeta de destino
    if (move_uploaded_file($pdfFile['tmp_name'], $filePath)) {
        // Preparar la consulta SQL para registrar el PDF en la base de datos
        $sql = "INSERT INTO pdf_files (action_id, file_name, file_path) VALUES (:action_id, :file_name, :file_path)";
        $stmt = $conn->prepare($sql);
        
        // Ejecutar la consulta
        $stmt->bindParam(':action_id', $actionId);
        $stmt->bindParam(':file_name', $fileName);
        $stmt->bindParam(':file_path', $filePath);

        if ($stmt->execute()) {
            echo 'PDF subido y registrado correctamente.';
            // Redirigir de vuelta a la vista de la OP
            header("Location: ../../views/produccion/indexP.php?action=viewPDF&id=$actionId");
            exit;
        } else {
            echo 'Error al registrar el archivo en la base de datos.';
        }
    } else {
        echo 'Error al mover el archivo.';
    }
}
?>
