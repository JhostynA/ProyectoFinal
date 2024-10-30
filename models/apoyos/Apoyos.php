<?php

require_once '/xampp/htdocs/LinoFino/models/Conexion.php'; // Asegúrate de que la ruta sea correcta

class Apoyos {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->getConexion();
    }

    public function registrar($ape_paterno, $ape_materno, $nombres, $documento) {
        // Verificar si el registro ya existe y no está eliminado
        $query = "SELECT * FROM apoyos WHERE documento = :documento AND eliminado = 0";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['documento' => $documento]);
        
        if ($stmt->rowCount() > 0) {
            return "El registro ya existe y no ha sido eliminado.";
        }

        // Insertar nuevo registro
        $query = "INSERT INTO apoyos (ape_paterno, ape_materno, nombres, documento, create_at) VALUES (:ape_paterno, :ape_materno, :nombres, :documento, NOW())";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'ape_paterno' => $ape_paterno,
            'ape_materno' => $ape_materno,
            'nombres' => $nombres,
            'documento' => $documento,
        ]);
        return "Registro insertado correctamente.";
    }

    public function listar() {
        $query = "SELECT * FROM apoyos WHERE eliminado = 0";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar($idapoyo, $ape_paterno, $ape_materno, $nombres, $documento) {
        $query = "UPDATE apoyos SET ape_paterno = :ape_paterno, ape_materno = :ape_materno, nombres = :nombres, documento = :documento, create_at = NOW() WHERE idapoyo = :idapoyo AND eliminado = 0";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'ape_paterno' => $ape_paterno,
            'ape_materno' => $ape_materno,
            'nombres' => $nombres,
            'documento' => $documento,
            'idapoyo' => $idapoyo,
        ]);
        return "Registro actualizado correctamente.";
    }

    public function eliminarLogico($idapoyo) {
        $query = "UPDATE apoyos SET eliminado = 1, deleted_at = NOW() WHERE idapoyo = :idapoyo AND eliminado = 0";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['idapoyo' => $idapoyo]);
        return "Registro eliminado lógicamente.";
    }
}
