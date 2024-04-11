<?php
// Incluir el archivo de conexión a la base de datos
include("../../config/conexion_bd.php");
$rut_usuario_activo = $_SESSION["usuario"]["rut_user"];

// Consulta para obtener el número de tickets sin asignar
$queryami = "SELECT COUNT(*) AS ami FROM vista_tickets_asignados WHERE usuarios_rut_user = '$rut_usuario_activo' ";
$resultadoami = $conexion->query($queryami);

// Verificar si la consulta fue exitosa
if ($queryami) {
    // Obtener el resultado de la consulta
    if ($resultadoami && $resultadoami->num_rows > 0) {
        $ami = $resultadoami->fetch_assoc()['ami'];
    } else {
        $ami = 0; // Asume 0 si no se encuentran registros
    }
    
    // Cerrar la consulta
    $resultadoami->close();

    // Devolver el resultado como respuesta AJAX en formato JSON
    echo json_encode(array('success' => true, 'ami' => $ami));
} else {
    // En caso de error, devolver un mensaje de error en formato JSON
    echo json_encode(array('success' => false, 'message' => 'Error al obtener el número de tickets total.'));
}


// Cerrar la conexión a la base de datos
$conexion->close();





?>












