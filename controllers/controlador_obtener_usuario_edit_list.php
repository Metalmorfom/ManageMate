<?php
session_start();
// Incluye el archivo de conexión a la base de datos
include("../config/conexion_bd.php"); // Incluye el archivo de conexión a la base de datos.

// Obtener el RUT del usuario desde la solicitud GET (asegúrate de validar y limpiar los datos)
$rutUsuario = $_GET['rut_user'];


$sql = "SELECT nombre, s_nombre, ap_paterno, ap_materno, correo, fecha_expiracion, telefono, id_codigo, id_estado FROM usuarios WHERE rut_user = ?";


// Preparar la consulta
if ($stmt = $conexion->prepare($sql)) {
    // Vincular el parámetro (RUT del usuario) a la consulta
    $stmt->bind_param("s", $rutUsuario);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado de la consulta
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtener los datos del usuario como un arreglo asociativo
        $usuario = $result->fetch_assoc();

        // Convertir los datos a JSON y enviarlos como respuesta
        echo json_encode($usuario);
    } else {
        // El usuario no se encontró en la base de datos
        echo json_encode(array('error' => 'Usuario no encontrado'));
    }

   
} else {
    // Error al preparar la consulta
    echo json_encode(array('error' => 'Error al preparar la consulta'));
}



// Cerrar la conexión a la base de datos
$conexion->close();
?>
