<?php
include("../../config/conexion_bd.php");

// Obtener el nombre de usuario de la solicitud AJAX
$nombreUsuario = json_decode(file_get_contents('php://input'))->nombreUsuario;

$response = ['usuario' => null]; // Respuesta predeterminada

if (!empty($nombreUsuario)) {
    // Preparar la consulta
    $query = "SELECT * FROM usuarios WHERE rut_user = ?";
    $stmt = $conexion->prepare($query);
    
    // Vincular parámetros y ejecutar
    $stmt->bind_param("s", $nombreUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verificar si existe algún usuario con el nombre proporcionado
    if ($result->num_rows > 0) {
        // Obtener la información del usuario
        $usuario = $result->fetch_assoc();
        $response['usuario'] = $usuario;
    }
    
    $stmt->close();
}

$conexion->close();

// Establecer encabezado para responder con JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
