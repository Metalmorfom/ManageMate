<?php
// controlador_cargar_permisos.php
session_start();
// Incluye el archivo de conexión a la base de datos
require_once("../config/conexion_bd.php");

// Asegúrate de obtener y validar el RUT del usuario de alguna manera.
// Aquí, se espera que el RUT del usuario se pase como un parámetro GET en la URL.
$rutUsuario = isset($_GET['rut_user']) ? $_GET['rut_user'] : '';

// Si el RUT del usuario no se proporciona, devuelve un error.
if (empty($rutUsuario)) {
    echo json_encode(array("error" => "RUT del usuario no proporcionado."));
    exit;
}

// Consulta SQL para obtener los permisos no asignados al usuario
$sql = "SELECT p.id_permiso_indi, p.nombre, p.tipo, p.detalle
        FROM permisos_indi p
        WHERE p.id_permiso_indi NOT IN (
            SELECT pu.id_permiso_indi
            FROM permisos_usuario pu
            WHERE pu.rut_user = ?)";

// Preparar la consulta SQL
if ($stmt = $conexion->prepare($sql)) {
    // Vincular el RUT del usuario a la consulta
    $stmt->bind_param("s", $rutUsuario);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->get_result();

    // Verificar si hay permisos disponibles
    if ($result->num_rows > 0) {
        $permisos = array();
        while ($fila = $result->fetch_assoc()) {
            $permisos[] = $fila;
        }

        // Devolver los permisos en formato JSON
        echo json_encode($permisos);
    } else {
        // Devolver un array vacío si no hay permisos disponibles
        echo json_encode(array());
    }

    // Cerrar la consulta preparada
    $stmt->close();
} else {
    // Manejar errores en la preparación de la consulta
    echo json_encode(array("error" => "Error en la preparación de la consulta SQL."));
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
