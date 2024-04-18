<?php
// buscar_cliente.php
include("../../config/conexion_bd.php");

// Obtener el rut del usuario de la solicitud POST
$empresa_usuario = $_POST['empresa_usuario'];

// Consulta SQL segura utilizando consultas preparadas
$sql = "SELECT e.nombre, e.giro_comercial, e.direc_casa_matriz, e.correo, e.telefono, c.nombre AS nombre_comuna, ci.nombre AS nombre_ciudad, r.nombre AS nombre_region
        FROM empresa AS e
        INNER JOIN comuna AS c ON e.id_comuna = c.id_comuna 
        INNER JOIN ciudad AS ci ON c.id_ciudad = ci.id_ciudad 
        INNER JOIN region AS r ON ci.id_region = r.id_region 
        WHERE rut_empresa = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $empresa_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si se encontraron resultados, obtener los datos del primer registro
    $row = $result->fetch_assoc();
    $datos_usuario = array(
        'nombre' => $row['nombre'],
        'giro_comercial' => $row['giro_comercial'],
        'direc_casa_matriz' => $row['direc_casa_matriz'],
        'correo' => $row['correo'],
        'telefono' => $row['telefono'],
        'nombre_comuna' => $row['nombre_comuna'],
        'nombre_ciudad' => $row['nombre_ciudad'],
        'nombre_region' => $row['nombre_region']
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
