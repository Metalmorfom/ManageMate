<?php
include("../config/conex_db.php"); // Incluye el archivo de conexión a la base de datos.

if (isset($_POST["btnRecuperar"])) {
    $correo = isset($_POST["correo"]) ? $_POST["correo"] : null;
    if (empty($_POST["correo"])) {
        
        // Comprueba si el campo de correo está vacío y muestra un mensaje de error si es así.
        $mensaje_alerta = '<div class="alert alert-danger">Rellena el campo de correo.</div>';
    }else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje_alerta ='<div class="alert alert-danger">El formato del correo electrónico no es válido.</div>';
    }
    else {
       

        // Prepara una consulta SQL para verificar si el correo existe en la base de datos y obtener el rut_user.
        $stmt = $conexion->prepare("SELECT rut_user, correo,nombre,ap_paterno FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Si el correo existe en la base de datos, obtén los valores del resultado.
            $row = $result->fetch_assoc();
            $rut_user = $row['rut_user'];
            $correo = $row['correo'];
            $nombre = $row['nombre'];
            $ap_paterno = $row['ap_paterno'];

             // Antes de generar un nuevo token, elimina todos los tokens existentes del mismo usuario.
             $stmt_delete_tokens = $conexion->prepare("DELETE FROM tokens WHERE id_usuario = ?");
             $stmt_delete_tokens->bind_param("s", $rut_user);
             $stmt_delete_tokens->execute();


            // Genera un token de recuperación de contraseña.
            $token_recuperacion = bin2hex(random_bytes(32)); // Genera un token de 64 caracteres en formato hexadecimal

            // Obtiene la hora actual
            $hora_actual = date('Y-m-d H:i:s');

            // Establece la fecha de expiración del token (por ejemplo, +5 Minutos después de la generación)
            $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+60 minutes', strtotime($hora_actual)));

            // Inserta el token en la tabla "tokens"
            $stmt = $conexion->prepare("INSERT INTO tokens (token, fecha_expiracion, tipo, id_usuario) VALUES (?, ?, 'restauracion', ?)");
            $stmt->bind_param("sss", $token_recuperacion, $fecha_expiracion, $rut_user);
            $stmt->execute();

            // Envía un correo al usuario con un enlace que incluye el token para restablecer la contraseña.
            // Aquí debes implementar el envío de correo.

            $mensaje_alerta = '<div class="alert alert-success">Se ha enviado a su correo con instrucciones para restablecer la contraseña.</div>';



        // Construye el enlace de verificación con el token
        $enlace_verificacion = "http://www.managemate.cl/SAP_CAPS/views/reset_pwd.php?token=" . $token_recuperacion;
          
    
    
            
        //Variables Globales Para crear mail 
        $GM_addEmbeddedImagen_ruta ='images/logo_mail.png'; // ruta de imagen firma
        $GM_addEmbeddedImagen_nombre ='logo_mail_acis'; // nombre de embebida imagen firma

        $GM_addEmbeddedImagen_ruta2 = "";
        $GM_addEmbeddedImagen_nombre2 = "";
        $GM_addAdress_Correo = $correo; // Cambia esto al destinatario real
        $GM_addAdress_nombre = $nombre; // Cambia esto al destinatario real
        $GM_addAdress_ap_paterno = $ap_paterno; // Cambia esto al destinatario real
        $GM_Subject = 'Recuperar Contraseña - Soporte ManageMate'; //asunto del mail
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
            <h2>Recuperación de Contraseña</h2>
            <p>¡Hola ' . $GM_addAdress_nombre . '!</p>

            <p>Recibes este correo porque has solicitado recuperar tu contraseña en ManageMate. Si no has realizado esta solicitud, puedes ignorar este mensaje.</p>
            <p>Para restablecer tu contraseña, haz clic en el siguiente enlace:</p>
            <p><a href="' . $GM_enlace_verificacion . '">Recuperar Contraseña</a></p>
           
            <p>Si tienes algún problema con el enlace, copia y pega la siguiente URL en tu navegador:</p>
            <p>' .  $enlace_verificacion . '</p>
            <p>Por razones de seguridad, este enlace caducara pronto. No compartas este enlace con nadie.</p>
            <p>Si no has solicitado esta recuperación de contraseña, por favor, contacta a nuestro equipo de soporte inmediatamente.</p>
            <p>Agradecemos que uses nuestros servicios.</p>
            
            <table>
                <tr>
                    <td>
                        <img src="cid:' . $GM_addEmbeddedImagen_nombre . '" alt="Logo Remitente">
                    </td>
                    <td>
                        Información de contacto:
                        <br>
                        Correo: info@ManageMate.cl
                        <br>
                        Soporte: Soporte ManageMate
                        Web: www.ManageMate.cl
                    </td>
                </tr>
            </table>
            <!-- Resto del contenido del correo -->
        </body>
        </html>';
        
        
            include("controlador_smtp.php");
    
    








        }else {
            $mensaje_alerta ='<div class="alert alert-danger">usuario no existe.</div>';
        }
    }
}
?>
