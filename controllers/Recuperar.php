<?php
require("../config/conex_db.php");
$hora_actual = new DateTime();
$hora_actual = $hora_actual->format('Y-m-d H:i:s');

$showForm = false;
$message = "";
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Consulta para obtener el ID de usuario
    $stmt = $conexion->prepare("SELECT id_usuario FROM tokens WHERE token = ? AND tipo = 'restauracion' AND fecha_expiracion > ?");
    $stmt->bind_param("ss", $token, $hora_actual);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $id_usuario = $row['id_usuario'];

        // Consulta para obtener el nombre de usuario usando el ID de usuario
        $stmt = $conexion->prepare("SELECT User FROM View_Users WHERE rut_user = ?");
        $stmt->bind_param("s", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $nombre_usuario = $row['User'];

            if (isset($_POST['contrasenia'])) {
                $nueva_contrasenia = $_POST['contrasenia'];

                // Llamar al procedimiento almacenado para cambiar la contraseña
                $stmt = $conexion->prepare("CALL CambiarContrasenia(?, ?)");
                $stmt->bind_param("ss", $nombre_usuario, $nueva_contrasenia);
                $stmt->execute();

                // Verificar si la ejecución fue exitosa
                if ($stmt->affected_rows == 0) {
                    // El procedimiento se ejecutó correctamente
                    // Puedes realizar acciones adicionales si es necesario
                    // Por ejemplo, eliminar el token

                    $stmt = $conexion->prepare("DELETE FROM tokens WHERE token = ?");
                    $stmt->bind_param("s", $token);
                    $stmt->execute();

                    $message = "¡Tu contraseña ha sido restablecida! <a href='../index.php'>Ir al inicio de sesión</a>";
                } else {
                    // Hubo un error al ejecutar el procedimiento almacenado
                    $message = "Error al cambiar la contraseña. Por favor, inténtalo de nuevo.";
                }
            } else {
                $showForm = true;
            }
        } else {
            $message = "Error al obtener el nombre de usuario.";
        }
    } else {
        $message = "Solicitud no válida o expirada. Verifica tu enlace de restablecimiento.";
    }
} else {
    $message = "Solicitud no encontrada. Verifica tu enlace de restablecimiento.";
}

?>
