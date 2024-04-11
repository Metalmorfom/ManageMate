<?php
// Incluir el archivo de conexión a la base de datos
include("../../config/conexion_bd.php");

// Consulta para obtener el número de tickets sin asignar
$queryTotalTickets = "SELECT COUNT(*) AS total FROM vista_total_tickets ";
$resultadoTotalTickets = $conexion->query($queryTotalTickets);

// Verificar si la consulta fue exitosa
if ($queryTotalTickets) {
    // Obtener el resultado de la consulta
    $totalTickets = $resultadoTotalTickets->fetch_assoc()['total'];
    
    // Cerrar la consulta
    $resultadoTotalTickets->close();

    // Devolver el resultado como respuesta AJAX en formato JSON
    echo json_encode(array('success' => true, 'total' => $totalTickets));
} else {
    // En caso de error, devolver un mensaje de error en formato JSON
    echo json_encode(array('success' => false, 'message' => 'Error al obtener el número de tickets total.'));
}


// Cerrar la conexión a la base de datos
$conexion->close();




?>


