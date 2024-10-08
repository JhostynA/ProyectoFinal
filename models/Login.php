<?php

require_once 'Conexion.php';

class Login extends Conexion{

  private $pdo;

  public function __CONSTRUCT(){
    $this->pdo = parent::getConexion();
  }

  public function login($params = []):array{
    try{
      $query = $this->pdo->prepare("call spu_colaboradores_login(?)");
      $query->execute(array($params['nomusuario']));
      return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(Exception $e){
      die($e->getMessage());
    }
  }

 /*  public function add($params = []):int{
    $idcolaboradores = null;
    try{
      $query = $this->pdo->prepare("call spu_colaboradores_registrar(?,?,?)");
      $query->execute(
        array(
          $params['idpersona'],
          $params['nomusuario'],
          password_hash($params['passusuario'], PASSWORD_BCRYPT),
        )
      );
      $row = $query->fetch(PDO::FETCH_ASSOC);
      $idcolaboradores = $row['idcolaboradores'];
    }
    catch(Exception $e){
      $idcolaboradores = -1;
    }

    return $idcolaboradores;
  } */

}

