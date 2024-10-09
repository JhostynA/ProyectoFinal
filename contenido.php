<?php
session_start(); 


if (!isset($_SESSION['login']) || (isset($_SESSION['login']) && !$_SESSION['login']['permitido'])){
    
    header('Location:index.php');
}

$host = "http://localhost/LinoFino";
?>



<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contenido</title>
    <link rel="stylesheet" href="<?= $host ?>/js/">
    <link href="<?= $host ?>/css/styles.css" rel="stylesheet"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Librería de iconos (botón de hamburguesa) -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
   
</head>

    <body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        
        <a class="navbar-brand ps-3">Lino Fino</a>
       
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
            </div>
        </form>
        
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i>
                    <?= $_SESSION['login']['nombres'] ?>
                    <?= $_SESSION['login']['apepaterno'] ?> 
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="<?= $host ?>/controllers/login.ct.php?operation=destroy">Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Inicio</div>
                        <a class="nav-link" href="<?= $host ?>/dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                            Dashboard
                        </a>
                              
                        <div class="sb-sidenav-menu-heading">Módulos</div>
                        <a class="nav-link" href="<?= $host ?>/views/produccion/indexP.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Producción
                        </a>

                        <a class="nav-link" href="<?= $host ?>/views/personas/">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Personas
                        </a>
                        <a class="nav-link" href="<?= $host ?>/views/operaciones/">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Operaciones
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">

        
        