<?php
// validar_busqueda.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../../config/conexion_bd.php");

$ticketNumber = isset($_POST['filtro_numero_ticket']) ? $_POST['filtro_numero_ticket'] : '';
$cliente = isset($_POST['filtro_cliente']) ? $_POST['filtro_cliente'] : '';

if ($cliente === 'Servicios Gestionados') {
    $sql = "SELECT COUNT(*) as ticketCount FROM ticket WHERE id_ticket = '$ticketNumber'";
} else {
    // Ajusta la consulta para incluir la validación del rut de la empresa
    $rutEmpresa = $cliente;
    $sql = "SELECT COUNT(*) as ticketCount FROM ticket WHERE id_ticket = '$ticketNumber' AND rut_empresa = '$rutEmpresa'";
}

$result = $conexion->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $ticketCount = $row['ticketCount'];

    if ($ticketCount > 0) {
        echo "true";
    } else {
        echo "false";
    }
} else {
    echo "false";
}
?>
