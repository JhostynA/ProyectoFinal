<?php
session_start();
require_once '../models/Login.php';
$Login = new Login();


if (isset($_GET['operation'])){

  switch($_GET['operation']){
    case 'login':
     
      $login = [
        "permitido"           => false,
        "apepaterno"          => "",
        "nombres"             => "",
        "idcolaboradores"     => "",
        "status"              => ""
      ];

      $row = $Login->login(['nomusuario' => $_GET['nomusuario']]);

      if (count($row) == 0){
        $login["status"] = "No existe el usuario";
      }else{
        $claveEncriptada = $row[0]['passusuario'];  
        $claveIngreso = $_GET['passusuario'];       

        if (password_verify($claveIngreso, $claveEncriptada)){
          $login["permitido"] = true;
          $login["apepaterno"] = $row[0]["apepaterno"];
          $login["nombres"] = $row[0]["nombres"];
          $login["idcolaboradores"] = $row[0]["idcolaboradores"];
        }else{
          $login["status"] = "Contraseña incorrecta";
        }
      }

      
      $_SESSION['login'] = $login;
      echo json_encode($login);
      break;
    
    case 'destroy':
      session_unset();
      session_destroy();
      header('Location:http://localhost/LinoFino');
      break;
  }
}


/* if (isset($_POST['operation'])){

  switch($_POST['operation']){
    case 'add':
      $datos = [
        "idpersona"   => $_POST['idpersona'],
        "nomusuario"  => $_POST['nomusuario'],
        "passusuario" => $_POST['passusuario']
      ];
      $idobtenido = $colaboradores->add($datos); 
      echo json_encode(["idcolaboradores" => $idobtenido]);
      break;
  }

} */
