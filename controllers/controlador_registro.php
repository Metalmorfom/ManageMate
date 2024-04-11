<?php

include("../config/conexion_bd.php");
if (isset($_POST["btnRegistrarse"])) {
    // Verifica si el botón "Registrarse" se ha presionado.

    $rut_user = $_POST["rut_user"];
    $nombre = $_POST["nombre"];
    $s_nombre = $_POST["s_nombre"];
    $ap_paterno = $_POST["ap_paterno"];
    $ap_materno = $_POST["ap_materno"];
    $correo = $_POST["correo"];
    $contrasenia = $_POST["contrasenia"];
    $telefono = $_POST["telefono"];
    $fecha_expiracion = $_POST["fecha_expiracion"];
    $id_rol = $_POST["id_rol"];
    $id_codigo = $_POST["id_codigo"];
    $_usuario = $_POST['usuario'];
    
    
    // Establece el valor predeterminado "4 Verificar Email" para id_estado
//1	Activo
//2	Inactivo
//3	Bloqueado
//4	Verificar Email
//5	Suspensión temporal
//6	Eliminado
//7	Cuenta Nueva
//8	Pendiente de revisión
//9	Premium o Suscripción
//10 Expirado
//11 Restablecimiento de contraseña
//12 Baneado
    $id_estado = 1;

    // Genera el token de verificación de correo
    $token_verificacion = bin2hex(random_bytes(32)); // Genera un token de 64 caracteres en formato hexadecimal

    // Genera la fecha de creación en el backend (usando la fecha actual) para el usuario.
    $fecha_creacion = date('Y-m-d H:i:s');
    

// Establece la zona horaria a Santiago, Chile
date_default_timezone_set('America/Santiago');

// Genera la fecha de expiración en el backend (5 minutos a partir de ahora en la zona horaria configurada)
$fecha_expiracion_token = date('Y-m-d H:i:s', strtotime('+5 minutes'));




    // Hashea la contraseña para mayor seguridad.
    //$contrasenia_hasheada = password_hash($contrasenia, PASSWORD_BCRYPT);
    
    try {
    // Prepara la consulta SQL para insertar el usuario en la tabla usuarios.
    // Preparar la llamada al procedimiento almacenado
    $stmt = $conexion->prepare("CALL crear_usuario_ope(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssiisss", $rut_user, $contrasenia, $nombre, $s_nombre, $ap_paterno, $ap_materno, $correo, $fecha_creacion,  $fecha_expiracion, $id_rol, $telefono, $id_codigo, $id_estado, $_usuario);
    
    if ($stmt->execute()) {
        // Inserta el token de verificación en la tabla tokens.
        $stmt = $conexion->prepare("INSERT INTO tokens (token, fecha_expiracion, tipo, id_usuario) VALUES (?, ?, 'verificacion', ?)");
        $stmt->bind_param("sss", $token_verificacion, $fecha_expiracion_token, $rut_user);
        $stmt->execute();

        // Construye el enlace de verificación con el token
    $enlace_verificacion = "http://www.managemate.cl/Proyect_increible/controllers/verificar.php?token=" . $token_verificacion;
        // Muestra un mensaje de éxito si la cuenta se crea correctamente.
        echo '<div class="alert alert-success">Cuenta creada con éxito.</div>';
        // Envía el correo de verificación a $correo (debes implementar el envío de correo aquí).


        
    //Variables Globales Para crear mail 
    $GM_addEmbeddedImagen_ruta ='images/logo_mail.png'; // ruta de imagen firma
    $GM_addEmbeddedImagen_nombre ='logo_mail_acis'; // nombre de embebida imagen firma

    $GM_addEmbeddedImagen_ruta2 = '';
    $GM_addEmbeddedImagen_nombre2 = '';

    $GM_addAdress_Correo = $correo; // Cambia esto al destinatario real
    $GM_addAdress_nombre = $nombre; // Cambia esto al destinatario real
    $GM_addAdress_ap_paterno = $ap_paterno; // Cambia esto al destinatario real
    $GM_Subject = 'Validar Email Soporte Acis'; //asunto del mail
    $GM_enlace_verificacion = $enlace_verificacion; //enlace de verificacion
    // cuerpo del correo
    $GM_Body = '
    <html>
    <head>
        <style>
            /* Estilos CSS personalizados si es necesario */
        </style>
    </head>
    <body>
        <h3>Por favor, verifica tu correo electrónico:</h3>
        <p>Por favor, haz clic en el siguiente enlace o cópialo en el navegador para verificar tu correo electrónico acis: <a href="' . $GM_enlace_verificacion . '">Verificar Email</a></p>
        <table>
            <tr>
                <td>
                    <img src="cid:' . $GM_addEmbeddedImagen_nombre . '" alt="Logo Remitente">
                </td>
                <td>
                    Información de contacto:
                    <br>
                    Correo: info@acis.cl
                    <br>
                    Soporte: Soporte Acis
                    web : www.acis.cl
                </td>
            </tr>
        </table>
        <!-- Resto del contenido del correo -->
    </body>
    </html>';
    
    
        include("controlador_smtp.php");







    }else {
        // Si la consulta no se ejecuta correctamente, se lanza una excepción con el mensaje de error de MySQL.
        throw new Exception("Error en la consulta: " . $stmt->error);
    }
}catch (Exception $e) {
    // Captura cualquier excepción que se produzca en el bloque "try"

    if (strpos($e->getMessage(), "Duplicate entry") !== false) {
        // Se detectó una violación de clave primaria o clave única
        if (strpos($e->getMessage(), "PRIMARY") !== false) {
            // Violación de clave primaria
            echo "El Rut ya está en uso.";
        } elseif (strpos($e->getMessage(), "usuarios_telefono__uk") !== false) {
            // Violación de clave única en el campo "telefono"
            echo "El número de teléfono ya está en uso.";
        } elseif (strpos($e->getMessage(), "usuarios_correo__uk") !== false) {
            echo "El Correo esta en Uso.";
        }
    } else {
        // Otro tipo de error
        echo "Error inesperado: " . $e->getMessage();
    }



}
}

?>