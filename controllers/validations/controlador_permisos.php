<?php
// controlador_permisos.php

// Función para verificar si un usuario tiene los permisos requeridos
function verificarPermisos($permisosRequeridos) {

    // Incluir la conexión a la base de datos 
include("../config/Guard_conexion.php");

    // Rut del usuario actual
$rutUsuario = $_SESSION["usuario"]["rut_user"];

    // Consulta SQL para obtener los permisos asignados al usuario
    $consulta = "SELECT id_permiso_indi FROM permisos_usuario WHERE rut_user = ?";

    // Preparar la consulta
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $rutUsuario);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $resultado = $stmt->get_result();

    // Obtener los permisos del usuario como un array
    $permisosUsuario = [];
    while ($fila = $resultado->fetch_assoc()) {
        $permisosUsuario[] = $fila['id_permiso_indi'];
    }

 // Verificar si el usuario tiene todos los permisos requeridos
$tieneTodosLosPermisos = true;
foreach ($permisosRequeridos as $permiso) {
    if (!in_array($permiso, $permisosUsuario)) {
        $tieneTodosLosPermisos = false;
        break; // detiene la verificación si falta uno de los permisos requeridos
    }
}

if ($tieneTodosLosPermisos) {
    return true;
} else {
    return false;
}
    
 
}


?>