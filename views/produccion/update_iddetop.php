<?php
session_start(); // Si utilizas sesiones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $iddetop = $data['iddetop'] ?? 0;

    // Guardar en una variable global (como sesión)
    $_SESSION['iddetop'] = $iddetop;

    // Responder con JSON
    echo json_encode(['success' => true, 'iddetop' => $iddetop]);
}
