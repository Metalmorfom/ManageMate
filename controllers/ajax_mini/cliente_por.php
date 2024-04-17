<?php
// buscar_cliente.php
include("../../config/conexion_bd.php");

// Obtener el rut del usuario de la solicitud POST
$rut_cliente = $_POST['cliente_usuario'];

// Consulta SQL segura utilizando consultas preparadas
$sql = "SELECT COALESCE(nombre, 'no hay datos') AS nombre, COALESCE(s_nombre, 'no hay datos') AS s_nombre, COALESCE(apellidos, 'no hay datos') AS apellidos, COALESCE(correo, 'no hay datos') AS correo, COALESCE(telefono, 'no hay datos') AS telefono FROM cliente WHERE rut_cliente = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $rut_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si se encontraron resultados, obtener los datos del primer registro
    $row = $result->fetch_assoc();
    $datos_usuario = array(
        'nombre' => $row['nombre'],
        's_nombre' => $row['s_nombre'],
        'apellidos' => $row['apellidos'],
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
?>
