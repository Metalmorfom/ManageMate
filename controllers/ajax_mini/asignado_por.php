<?php
// buscar_cliente.php
include("../../config/conexion_bd.php");

// Obtener el rut del usuario de la solicitud POST
$asignado_usuario = $_POST['asignado_usuario'];

// Consulta SQL segura utilizando consultas preparadas
$sql = "SELECT nombre,s_nombre,ap_paterno,ap_materno, correo,telefono FROM View_Users WHERE rut_user = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $asignado_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si se encontraron resultados, obtener los datos del primer registro
    $row = $result->fetch_assoc();
    $datos_usuario = array(
        'nombre' => $row['nombre'],
        's_nombre' => $row['s_nombre'],
        'ap_paterno' => $row['ap_paterno'],
        'ap_materno' => $row['ap_materno'],

        'correo' => $row['correo'],
        'telefono' => $row['telefono']
    );
    // Devolver los datos del usuario en formato JSON
    echo json_encode($datos_usuario);
} else {
    // Si no se encontraron resultados, devolver un mensaje de error
    echo json_encode(array('error' => 'No se encontraron datos para el usuario.'));
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
