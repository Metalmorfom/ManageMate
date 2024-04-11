<?php
// Incluir el archivo de conexión a la base de datos
include("../../config/conexion_bd.php");

// Consulta para obtener el número de tickets sin asignar
$queryResuelto = "SELECT COUNT(*) AS Resuelto FROM vista_tickets_resueltos_count";
$resultadoResuelto = $conexion->query($queryResuelto);

// Verificar si la consulta fue exitosa
if ($queryResuelto) {
    // Obtener el resultado de la consulta
    $Resuelto = $resultadoResuelto->fetch_assoc()['Resuelto'];
    
    // Cerrar la consulta
    $resultadoResuelto->close();

    // Devolver el resultado como respuesta AJAX en formato JSON
    echo json_encode(array('success' => true, 'resuelto' => $Resuelto));
} else {
    // En caso de error, devolver un mensaje de error en formato JSON
    echo json_encode(array('success' => false, 'message' => 'Error al obtener el número de tickets resuelto.'));
}


// Cerrar la conexión a la base de datos
$conexion->close();






?>


