<?php
// Incluir el archivo de conexión a la base de datos
include("../../config/conexion_bd.php");

// Consulta para obtener el número de tickets sin asignar
$querySinAsignar = "SELECT COUNT(*) AS sin_asignar FROM vista_tickets_sin_asignar";
$resultadoSinAsignar = $conexion->query($querySinAsignar);

// Verificar si la consulta fue exitosa
if ($resultadoSinAsignar) {
    // Obtener el resultado de la consulta
    $sinAsignar = $resultadoSinAsignar->fetch_assoc()['sin_asignar'];
    
    // Cerrar la consulta
    $resultadoSinAsignar->close();

    // Devolver el resultado como respuesta AJAX en formato JSON
    echo json_encode(array('success' => true, 'sin_asignar' => $sinAsignar));
} else {
    // En caso de error, devolver un mensaje de error en formato JSON
    echo json_encode(array('success' => false, 'message' => 'Error al obtener el número de tickets sin asignar.'));
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
