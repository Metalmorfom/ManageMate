<?php
include("../../config/conexion_bd.php");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el valor del parámetro enviado por Ajax
$nombreEmpresa = $_POST['nombre_empresa'];

// Consulta SQL para buscar empresas que coincidan con el nombre proporcionado
$sql = "SELECT rut_empresa, nombre FROM empresa WHERE nombre LIKE '%$nombreEmpresa%'";

$result = $conexion->query($sql);

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    // Arreglo para almacenar los resultados
    $empresas = array();

    // Recorrer los resultados y almacenarlos en el arreglo
    while($row = $result->fetch_assoc()) {
        // Agregar tanto el rut_empresa como el nombre al arreglo de empresas
        $empresas[] = array(
            'rut_empresa' => $row['rut_empresa'],
            'nombre' => $row['nombre']
        );
    }

    // Devolver los resultados en formato JSON
    echo json_encode($empresas);
} else {
    echo json_encode(array()); // Devolver un arreglo vacío si no se encontraron resultados
}

// Cerrar la conexión
$conexion->close();
?>
