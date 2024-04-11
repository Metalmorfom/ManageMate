<?php
// Incluir el archivo de conexión a la base de datos
include("../../config/conexion_bd.php");

// Consulta para obtener el número de tickets sin asignar
$queryPendiente = "SELECT COUNT(*) AS progreso FROM vista_tickets_pendientes ";
$resultadoPendiente = $conexion->query($queryPendiente);

// Verificar si la consulta fue exitosa
if ($queryPendiente) {
    // Obtener el resultado de la consulta
    $pendiente = $resultadoPendiente->fetch_assoc()['progreso'];
    
    // Cerrar la consulta
    $resultadoPendiente->close();

    // Devolver el resultado como respuesta AJAX en formato JSON
    echo json_encode(array('success' => true, 'pendiente' => $pendiente));
} else {
    // En caso de error, devolver un mensaje de error en formato JSON
    echo json_encode(array('success' => false, 'message' => 'Error al obtener el número de tickets pendiente.'));
}
// Cerrar la conexión a la base de datos
$conexion->close();
?>








