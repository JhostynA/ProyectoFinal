<?php

require_once 'C:/xampp/htdocs/LinoFino/models/apoyos/Apoyos.php'; // Asegúrate de que la ruta sea correcta

class ApoyoController {
    private $apoyoModel;

    public function __construct() {
        $this->apoyoModel = new Apoyos();
    }

    // Método para manejar solicitudes GET y mostrar apoyos
    public function listarApoyos() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $apoyos = $this->apoyoModel->listar(); // Llama al modelo para obtener los apoyos
            // Aquí puedes incluir lógica para renderizar la vista o devolver un JSON, según tu necesidad
            echo json_encode($apoyos); // Por ejemplo, si es para una API
        } else {
            http_response_code(405); // Método no permitido
            echo json_encode(['error' => 'Método no permitido']);
        }
    }

    // Método para manejar solicitudes POST y registrar un apoyo
    public function registrarApoyo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true); // Obtener los datos de la solicitud
            // Validar datos (aquí podrías agregar más validaciones según sea necesario)
            if (!isset($data['ape_paterno'], $data['ape_materno'], $data['nombres'], $data['documento'])) {
                http_response_code(400); // Bad request
                echo json_encode(['error' => 'Datos incompletos']);
                return;
            }

            // Llamar al modelo para registrar el apoyo
            $resultado = $this->apoyoModel->registrar(
                $data['ape_paterno'],
                $data['ape_materno'],
                $data['nombres'],
                $data['documento']
            );

            if ($resultado) {
                echo json_encode(['mensaje' => 'Apoyo registrado exitosamente']);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'Error al registrar el apoyo']);
            }
        } else {
            http_response_code(405); // Método no permitido
            echo json_encode(['error' => 'Método no permitido']);
        }
    }

    // Método para manejar solicitudes PUT y actualizar un apoyo
    public function actualizarApoyo() {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true); // Obtener los datos de la solicitud
            // Validar datos
            if (!isset($data['idapoyo'], $data['ape_paterno'], $data['ape_materno'], $data['nombres'], $data['documento'])) {
                http_response_code(400); // Bad request
                echo json_encode(['error' => 'Datos incompletos']);
                return;
            }

            // Llamar al modelo para actualizar el apoyo
            $resultado = $this->apoyoModel->actualizar(
                $data['idapoyo'],
                $data['ape_paterno'],
                $data['ape_materno'],
                $data['nombres'],
                $data['documento']
            );

            if ($resultado) {
                echo json_encode(['mensaje' => 'Apoyo actualizado exitosamente']);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'Error al actualizar el apoyo']);
            }
        } else {
            http_response_code(405); // Método no permitido
            echo json_encode(['error' => 'Método no permitido']);
        }
    }

    // Método para manejar solicitudes DELETE y eliminar un apoyo
    public function eliminarApoyo() {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true); // Obtener los datos de la solicitud
            // Validar datos
            if (!isset($data['idapoyo'])) {
                http_response_code(400); // Bad request
                echo json_encode(['error' => 'ID de apoyo requerido']);
                return;
            }

            // Llamar al modelo para eliminar el apoyo
            $resultado = $this->apoyoModel->eliminarLogico($data['idapoyo']);

            if ($resultado) {
                echo json_encode(['mensaje' => 'Apoyo eliminado exitosamente']);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'Error al eliminar el apoyo']);
            }
        } else {
            http_response_code(405); // Método no permitido
            echo json_encode(['error' => 'Método no permitido']);
        }
    }
}

// Ejemplo de uso del controlador
$controller = new ApoyoController();

// Aquí puedes decidir qué acción ejecutar según la ruta
// Esto podría hacerse con un sistema de enrutamiento más elaborado
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $controller->listarApoyos();
        break;
    case 'POST':
        $controller->registrarApoyo();
        break;
    case 'PUT':
        $controller->actualizarApoyo();
        break;
    case 'DELETE':
        $controller->eliminarApoyo();
        break;
    default:
        http_response_code(405); // Método no permitido
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
?>
