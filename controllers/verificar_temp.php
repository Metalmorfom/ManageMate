<?php
// Incluir el archivo de conexión a la base de datos
// Incluir el archivo de conexión a la base de datos
include("../config/conexion_bd.php");

// Obtener el nombre del archivo desde la solicitud
$nombreArchivo = isset($_GET['nombreArchivo']) ? $_GET['nombreArchivo'] : '';

// Preparar la consulta SQL para verificar la existencia del archivo
$query = $conexion->prepare("SELECT * FROM archivos_temporales WHERE nombre_temporal = ?");
$query->bind_param("s", $nombreArchivo);
$query->execute();
$result = $query->get_result();

// Verificar si se encontró el archivo
if ($result->num_rows > 0) {
    // Si se encontró el archivo, devolver 'true'
    echo "true";
} else {
    // Si no se encontró el archivo, devolver 'false'
    echo "false";
}

// Cerrar la conexión a la base de datos si es necesario
$conexion->close();
?>
