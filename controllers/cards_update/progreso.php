<?php
// Incluir el archivo de conexión a la base de datos
include("../../config/conexion_bd.php");

// Consulta para obtener el número de tickets sin asignar
$queryProgreso = "SELECT COUNT(*) AS progreso FROM vista_tickets_en_progreso ";
$resultadoProgreso = $conexion->query($queryProgreso);

// Verificar si la consulta fue exitosa
if ($queryProgreso) {
    // Obtener el resultado de la consulta
    $progreso = $resultadoProgreso->fetch_assoc()['progreso'];
    
    // Cerrar la consulta
    $resultadoProgreso->close();

    // Devolver el resultado como respuesta AJAX en formato JSON
    echo json_encode(array('success' => true, 'progreso' => $progreso));
} else {
    // En caso de error, devolver un mensaje de error en formato JSON
    echo json_encode(array('success' => false, 'message' => 'Error al obtener el número de tickets progreso.'));
}


// Cerrar la conexión a la base de datos
$conexion->close();
?>


