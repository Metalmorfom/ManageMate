<?php

include("../config/conexion_bd.php");




// Obtiene la hora actual
$hora_actual = date('Y-m-d H:i:s');

//echo 'La hora actual es: ' . $hora_actual;


if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conexion->prepare("SELECT id_usuario FROM tokens WHERE token = ? AND tipo = 'verificacion' AND fecha_expiracion > ?");
    $stmt->bind_param("ss", $token, $hora_actual);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $id_usuario = $row['id_usuario'];

        // Activa la cuenta del usuario en la tabla usuarios
        $stmt = $conexion->prepare("UPDATE usuarios SET id_estado = 1 WHERE rut_user = ?");
        $stmt->bind_param("s", $id_usuario);
        $stmt->execute();

        // Elimina el registro del token
        $stmt = $conexion->prepare("DELETE FROM tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "¡Tu cuenta ha sido verificada exitosamente! Puedes iniciar sesión.";
    } else {
        echo "Token no válido o expirado. Verifica tu enlace de verificación.";
    }
} else {
    echo "Token no encontrado. Verifica tu enlace de verificación. ";
}
