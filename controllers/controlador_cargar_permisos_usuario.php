<?php
// controlador_cargar_permisos_usuario.php
session_start();
// Incluye el archivo de conexión a la base de datos
require("../config/conexion_bd.php");

// Obtén el RUT del usuario desde la URL (asegúrate de validar y sanear este valor)
$rutUsuario = isset($_GET['rut_user']) ? $_GET['rut_user'] : '';

// Verifica si el RUT del usuario está presente
if (!$rutUsuario) {
    echo json_encode(array("error" => "RUT del usuario no proporcionado"));
    exit;
}

// Consulta SQL para obtener los permisos del usuario
$sql = "SELECT p.id_permiso_indi, p.nombre, p.tipo, p.detalle
        FROM permisos_indi p
        INNER JOIN permisos_usuario pu ON p.id_permiso_indi = pu.id_permiso_indi
        WHERE pu.rut_user = ?";

if ($stmt = $conexion->prepare($sql)) {
    // Asociar el RUT del usuario con el parámetro de la consulta
    $stmt->bind_param("s", $rutUsuario);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Almacenar los resultados en un array
        $permisos = array();
        while ($fila = $result->fetch_assoc()) {
            $permisos[] = $fila;
        }

        // Devolver los resultados en formato JSON
        echo json_encode($permisos);
    } else {
        // Manejar el error en la ejecución de la consulta
        echo json_encode(array("error" => "Error al ejecutar la consulta SQL"));
    }

    // Cerrar la consulta
    $stmt->close();
} else {
    // Manejar el error en la preparación de la consulta
    echo json_encode(array("error" => "Error en la preparación de la consulta SQL"));
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
