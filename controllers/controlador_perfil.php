<?php
include("../config/conexion_bd.php"); // Incluye el archivo de conexión a la base de datos.



// Obtiene los datos del usuario conectado desde la sesión
$usuarioConectado = $_SESSION['usuario'];

// Obtiene el ID del usuario conectado
$rut_user_conectado = $usuarioConectado['correo'];

// Obtén la información del usuario conectado desde la base de datos
$query = "SELECT usuarios.*, cod_telefono.codigo,usuarios.id_codigo 
            FROM usuarios 
            LEFT JOIN cod_telefono ON usuarios.id_codigo = cod_telefono.id_codigo 
            WHERE usuarios.correo = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $rut_user_conectado);
$stmt->execute();
$result = $stmt->get_result();

// Consulta los códigos de teléfono desde la base de datos.
$query_codigos = "SELECT id_codigo, codigo FROM cod_telefono";
$result_codigos = $conexion->query($query_codigos);

$mensaje_alerta = '';
if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $nombre = $row['nombre'];
    $s_nombre = $row['s_nombre'];
    $ap_paterno = $row['ap_paterno'];
    $ap_materno = $row['ap_materno'];
    $correo = $row['correo'];
    $telefono = $row['telefono'];
    $codigo = $row['codigo'];
    $id_selec_code =$row['id_codigo'];

    // Comprueba si se ha enviado un formulario para actualizar la información
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nuevoNombre = $_POST['nuevoNombre'];
        $nuevoSNombre = $_POST['nuevoSNombre'];
        $nuevoApPaterno = $_POST['nuevoApPaterno'];
        $nuevoApMaterno = $_POST['nuevoApMaterno'];
      
        $nuevoTelefono = $_POST['nuevoTelefono'];
        $id_codigo = $_POST['id_codigo'];



        // Actualiza la información del usuario en la base de datos
        $updateQuery = "UPDATE usuarios SET nombre = ?, s_nombre = ?, ap_paterno = ?, ap_materno = ?,telefono = ?,id_codigo = ? WHERE correo = ?";
        $updateStmt = $conexion->prepare($updateQuery);
        $updateStmt->bind_param("sssssss", $nuevoNombre, $nuevoSNombre, $nuevoApPaterno, $nuevoApMaterno, $nuevoTelefono, $id_codigo, $rut_user_conectado);

        if ($updateStmt->execute()) {
            $mensaje_alerta = "Información del usuario actualizada con éxito.";
            echo '<script>window.location.href = "inicio.php";</script>';
           
        } else {
            $mensaje_alerta = "Error al actualizar la información del usuario: " . $conexion->error;
        }
       
    }
}
