<?php

require_once '/xampp/htdocs/LinoFino/models/Conexion.php';

class Persona extends Conexion
{

    private $pdo;

    public function __CONSTRUCT()
    {
        $this->pdo = parent::getConexion();
    }

    // Método para buscar personas usando el procedimiento almacenado
    public function buscar($query)
    {
        try {
            // Llamar al procedimiento almacenado spu_buscar_personas
            $sql = "CALL spu_buscar_personas(?)";
            $cmd = $this->pdo->prepare($sql);
            $cmd->execute(array($query));
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Método para listar personas con un parámetro de búsqueda
    public function listar($query)
    {
        try {
            // Si no hay consulta, mostrar todas las personas
            if (empty($query)) {
                $query = '%';  // Usar '%' para listar todas las personas
            }
            $sql = "CALL spu_listar_personas(?)";
            $cmd = $this->pdo->prepare($sql);
            $cmd->execute(array($query));
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}


/* $persona = new Persona();

echo json_encode($persona->buscar('yupan')); */
