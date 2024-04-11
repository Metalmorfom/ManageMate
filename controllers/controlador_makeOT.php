<?php
// Incluir el archivo de configuración de GCP Storage
require '../vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

// Configuración de Google Cloud Storage
$projectId = 'tiketeraacis';
$bucketName = 'repositorioacis';
$keyFilePath = '../tiketeraacis-082c54670f64.json';

$storage = new StorageClient([
    'projectId' => $projectId,
    'keyFilePath' => $keyFilePath
]);

$bucket = $storage->bucket($bucketName);
//controlador_makeot.php

// Incluir la conexión a la base de datos 
include("../config/conexion_bd.php");

$usuario_activo = $_SESSION["usuario"]["nombre"] . " " . $_SESSION["usuario"]["ap_paterno"] . " " . $_SESSION["usuario"]["ap_materno"];

$rut_user_generador = $_SESSION["usuario"]["rut_user"];


// Consulta SQL function para obtener el último ticket creado
$query = "SELECT FN_generarNuevoTicket() AS formattedId";
$result = $conexion->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $formattedId = $row['formattedId'];
    $result->close(); // Cierra la consulta después de obtener los resultados
    // Resto del código...
} else {
    // Manejo de errores si la consulta falla
    echo "Error al ejecutar la función almacenada.";
}







// Consulta SQL para obtener las opciones de prioridad de la tabla "prioridad"
$query_prioridades = "SELECT id_prioridad as correl, nombre as atrib FROM PRIORIDAD;";
$result_prioridades = $conexion->query($query_prioridades);



$fecha_creacion = date('Y-m-d H:i:s');


// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los valores del formulario
    $estado_tk = $_POST["estado_tk"];
    $rut_empresa = $_POST["rutEmpresaHidden"];
    $id_ticket = $_POST["numero"];




    $rut_cliente = $_POST["cliente"];

    $resumen = $_POST["resumen"];
    $descripcion = $_POST["descripcion"];

    // Genera la fecha de creación en el backend (usando la fecha actual) para el usuario.
    $fecha_creacion = date('Y-m-d H:i:s');

    $query_user_creador = "SELECT CONCAT(nombre, ' ', ap_paterno, ' ', ap_materno) AS nombre_completo FROM usuarios WHERE rut_user = '$rut_user_generador'";
    $result_user_creador = $conexion->query($query_user_creador);

    // Verifica si la consulta fue exitosa
    if ($result_user_creador) {
        $row = $result_user_creador->fetch_assoc();
        $nombre_creador = $row['nombre_completo'];

        // Cierra la consulta después de obtener los resultados
        $result_user_creador->close();
    } else {
        // Manejo de errores si la consulta falla
        echo "Error en la consulta: " . $conexion->error;
    }


    $prioridad = $_POST["prioridad"];
    $modelo = $_POST["Modelo"];

    // Campos faltantes del formulario
    $Nombre_user_completo_afectado = $_POST["Nombre_user_completo_afectado"];
    $Mandante_afectado = $_POST["Mandante_afectado"]; // Cambiado a Nro_Componente para coincidir con el formulario
    $Cargo_afectado = $_POST["Cargo_afectado"];

    // Campos de actividades
    $interno = isset($_POST["actividades"]["recepcion"]) && in_array("interno", $_POST["actividades"]["recepcion"]) ? 1 : 0;
    $lavado_equipo = isset($_POST["actividades"]["recepcion"]) && in_array("Lavado Equipo", $_POST["actividades"]["recepcion"]) ? 1 : 0;


    $UPW = isset($_POST["actividades"]["ambiente"]) && in_array("UPW", $_POST["actividades"]["ambiente"]) ? 1 : 0;
    $PRB = isset($_POST["actividades"]["ambiente"]) && in_array("PRB", $_POST["actividades"]["ambiente"]) ? 1 : 0;
    $PRD = isset($_POST["actividades"]["ambiente"]) && in_array("PRD", $_POST["actividades"]["ambiente"]) ? 1 : 0;
    $QAS = isset($_POST["actividades"]["ambiente"]) && in_array("QAS", $_POST["actividades"]["ambiente"]) ? 1 : 0;


    $Creacion = isset($_POST["actividades"]["accion"]) && in_array("Creacion", $_POST["actividades"]["accion"]) ? 1 : 0;
    $desvinculacion = isset($_POST["actividades"]["accion"]) && in_array("desvinculacion", $_POST["actividades"]["accion"]) ? 1 : 0;
    $homologacion = isset($_POST["actividades"]["accion"]) && in_array("homologacion", $_POST["actividades"]["accion"]) ? 1 : 0;
    $reseteo = isset($_POST["actividades"]["accion"]) && in_array("reseteo", $_POST["actividades"]["accion"]) ? 1 : 0;

    $usuarios_rut_user = !empty($_POST['rut_user_asignado']) ? $_POST['rut_user_asignado'] : 'comodin_null';

    $Notas = $_POST["Notas_del_Trabajo"];



    $Rut_usuario_afectado = $_POST['Rut_usuario_afectado'];

    // Realiza la inserción en la tabla de tickets

    // Preparar la consulta de inserción
    $query_insertar = "INSERT INTO ticket (id_ticket, resumen, descripcion, fecha_creacion, rut_user_generador, id_estado_tk, rut_empresa, cliente_rut_cliente,usuarios_rut_user, id_prioridad,Rut_usuario_afectado, modelo, Nombre_user_completo_afectado, Mandante_afectado, Cargo_afectado, interno, lavado_equipo, UPW, PRB, PRD, QAS, Creacion, desvinculacion, homologacion, reseteo)
                  VALUES ('$id_ticket', '$resumen', '$descripcion', '$fecha_creacion', '$rut_user_generador', '$estado_tk', '$rut_empresa', '$rut_cliente','$usuarios_rut_user', '$prioridad','$Rut_usuario_afectado', '$modelo', '$Nombre_user_completo_afectado', '$Mandante_afectado', '$Cargo_afectado', $interno, $lavado_equipo, $UPW, $PRB, $PRD, $QAS, $Creacion, $desvinculacion, $homologacion, $reseteo)";

    if ($conexion->query($query_insertar) === TRUE) {
        // La inserción se realizó con éxito, puedes redirigir al usuario o mostrar un mensaje
        //echo "Ticket creado con éxito.";
        //  ('Nuevo'),
        //	('En Progreso'),
        //	('pendiente'),
        //	('Resuelto'),
        //	('Cancelado');

        if ($Notas != null) {
            $horaActual = date('Y-m-d H:i:s');

            $query_notas = $conexion->prepare("INSERT INTO NOTAS_TRAB (fecha_hora, descripcion, rut_user, id_ticket) VALUES (?, ?, ?, ?)");
            $query_notas->bind_param("ssss", $horaActual, $Notas, $rut_user_generador, $id_ticket);

            if ($query_notas->execute()) {
                // La consulta se ejecutó correctamente
                $query_notas->close();
            } else {
            }
        }






        $tipo_historico = "creacion";
        // Insertar historico creado
        $horaActual = date('Y-m-d H:i:s');
        $stmtInsertHistorico_creacion = $conexion->prepare("INSERT INTO historico (fecha_hora, tipo_historico, rut_empresa, rut_user, id_ticket, estado_actual) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtInsertHistorico_creacion->bind_param("sssssi", $horaActual, $tipo_historico, $rut_empresa, $rut_user_generador, $id_ticket, $estado_tk);

        if ($stmtInsertHistorico_creacion->execute() === TRUE) {
            if (!empty($usuarios_rut_user && $usuarios_rut_user !== 'comodin_null')) {
                // Código a ejecutar si la variable no está vacía

                $tipo_historico = "Asignación";

                $horaActual = date('Y-m-d H:i:s');
                $nuevaHoraActual = date('Y-m-d H:i:s', strtotime($horaActual) + 2);

                $stmtInsertHistorico_asignado = $conexion->prepare("INSERT INTO historico (fecha_hora, tipo_historico, rut_empresa, rut_user, id_ticket, estado_actual, rut_usuario_asignado) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmtInsertHistorico_asignado->bind_param("sssssis", $nuevaHoraActual, $tipo_historico, $rut_empresa, $rut_user_generador, $id_ticket, $estado_tk, $usuarios_rut_user);

                if ($stmtInsertHistorico_asignado->execute()) {
                    // Cerrar consulta preparada
                    $stmtInsertHistorico_asignado->close();


                    $Correousuario_asignado = $conexion->prepare("SELECT correo, nombre, ap_paterno, ap_materno FROM usuarios WHERE rut_user = ?");
                    $Correousuario_asignado->bind_param("s", $usuarios_rut_user);






                    $frond_estado = 'Nuevo';




                    if ($Correousuario_asignado->execute()) {

                        // Vincula el resultado a las variables
                        $Correousuario_asignado->bind_result($correo_user_asignado, $nombre, $ap_paterno, $ap_materno);

                        // Obtiene el resultado
                        $Correousuario_asignado->fetch();






                        //Variables Globales Para crear mail 
                        $GM_addEmbeddedImagen_ruta = '../images/logonav.png'; // ruta de imagen firma
                        $GM_addEmbeddedImagen_nombre = 'logo_mail_acis'; // nombre de embebida imagen firma

                        $GM_addEmbeddedImagen_ruta2 = '../images/footer_mail_ticket.png'; // ruta de imagen firma
                        $GM_addEmbeddedImagen_nombre2 = 'logo_mail_acis'; // nombre de embebida imagen firma

                        $GM_addAdress_Correo = $correo_user_asignado; // Cambia esto al destinatario real

                        $GM_addAdress_nombre = $nombre; // Cambia esto al destinatario real
                        $GM_addAdress_ap_paterno = $ap_paterno; // Cambia esto al destinatario real
                        $GM_Subject = 'El Requerimiento ' . $id_ticket . '  se ha asignado a usted'; //asunto del mail
                        //$GM_enlace_verificacion = $enlace_verificacion; //enlace de verificacion
                        // cuerpo del correo
                        $GM_Body = '<html><head>
                        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head><body><p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <style>
                                /* CSS Styles */
                                .baseline-img {
                                    align: baseline;
                                }
                        
                                .blue-bold-verdana {
                                    color: #0b5dff;
                                    font-family: verdana;
                                    font-weight: bold;
                                }
                        
                                .gray-verdana {
                                    color: #7e7e96;
                                    font-family: verdana;
                                }
                        
                                .orange-bold-verdana {
                                    color: #ff470e;
                                    font-family: verdana;
                                    font-weight: bold;
                                }
                        
                                .inside-list {
                                    list-style-position: inside;
                                }
                        
                                .gray-text {
                                    color: #7e7e96;
                                }
                        
                                .right-align {
                                    text-align: right;
                                }
                        
                                .blue-verdana {
                                    color: #1614aa;
                                    font-family: verdana;
                                    font-size: 12pt;
                                }
                            </style>
                        <table style="height: 616.8px;" border="0" width="500" cellpadding="0" align="center">
                        <tbody>
                        <tr style="height: 179.4px;">
                        <td style="height: 179.4px;"><img class="baseline-img" src="cid:' . $GM_addEmbeddedImagen_nombre . '" width="881" height="176"></td>
                        </tr>
                        <tr style="height: 288.2px;">
                        <td style="height: 288.2px;">
                        <p><span class="blue-bold-verdana">Estimad@, ' . $nombre . ' ' . $ap_paterno . ' <br></span></p>
                        <p><span class="gray-verdana">Se le informa que se ha asignado a usted <span class="orange-bold-verdana">Requerimiento N°' . $id_ticket . ' </span>, por lo que se le solicita que pueda proceder a realizar las gestiones pertinentes, con el fin de dar pronta solución.</span></p>
                        <p><span class="blue-bold-verdana">Detalles de la incidencia:</span></p>
                        <ul class="inside-list">
                        <li><span class="gray-verdana"><strong>Número: </strong> ' . $id_ticket . ' </span></li>
                        <li><span class="gray-verdana"><strong>Local:</strong> </span></li>
                        <li><span class="gray-verdana"><strong>Nombre del cliente:</strong>' . $nombre . ' ' . $ap_paterno . '  </span></li>
                        <li><span class="gray-verdana"><strong>Ubicación del Cliente: </strong> </span></li>
                        <li><span class="gray-verdana"><strong>Usuario asignado:</strong> </span></li>
                        <li><span class="gray-verdana"><strong>Descripción: </strong> ' . $descripcion . ' </span></li>
                        </ul>
                        <p><span class="blue-bold-verdana">Categorización del Requerimiento:</span></p>
                        <ul class="inside-list">
                        <li><span class="gray-verdana"><strong>Prioridad: </strong> Media</span></li>
                        </ul>
                        <p><span class="gray-text">Favor no responda este mensaje. En caso de Observaciones o consulta respecto a su Incidente, comunicarse con el responsable a través de los canales de Atención.</span></p>
                        <p></p><p><font size="2" color="#808080" face="helvetica">Puede ver todos los detalles del incidente siguiendo el enlace que se incluye a continuación:</font></p><font face="helvetica">
                        <a href="http://www.managemate.cl/sap_caps/views/busqueda.php?filtro_cliente=U2VydmljaW9zIEdlc3Rpb25hZG9z&filtro_numero_ticket=' . base64_encode($id_ticket) . '"
                         originalsrc="http://www.managemate.cl/sap_caps/views/busqueda.php?filtro_cliente=U2VydmljaW9zIEdlc3Rpb25hZG9z&filtro_numero_ticket=' . base64_encode($id_ticket) . '"
                          shash="nzlWKJdZ/YhyiSNl/Y1GCB0nEWZdZbtnzOI+btL1WEL2WPvO55CEf3IUzSwn7TVFNqCVaA7XS3uLavjQfMtEidySEfAQvnglo1A6cSnaah0vPn/E6mmmSnsMCvaCZl98swcHgwX5xReHPSVZTBIuZvNbJ6Uqn3RmFWE3uAxFR10=" style="background-color: #278efc;border: 1px solid #0368d4;color: #ffffff;font-size: 11px;font-family: Helvetica, Verdana, sans-serif;text-decoration: none; border-radius: 3px;-webkit-border-radius: 3px;-moz-border-radius: 3px;display: inline-block;padding: 5px;">Quiero acceder al Requerimiento</a></font><br><br><p><font size="3" color="#808080" face="helvetica"></font></p><p></p>
                        
                          <p></p>
                        </td>
                        </tr>
                        <tr style="height: 92.8px;">
                        <td style="height: 92.8px;">
                        <div>
                        <p class="right-align blue-verdana"><strong>Saluda Atentamente<br></strong></p>
                        </div>
                        <div>
                        <p class="right-align blue-verdana"><strong>Mesa de Ayuda ManageMate</strong></p>
                        </div>
                        </td>
                        </tr>
                        <tr style="height: 56.4px;">
                        <td style="height: 56.4px;"><img class="baseline-img" src="cid:' . $GM_addEmbeddedImagen_nombre . '" width="878" height="53"></td>
                        </tr>
                        </tbody>
                        </table><div>&nbsp;</div><div style="display:inline"></div></body></html>';
                    }





                    include('controlador_smtp.php');

                    // Cerrar la consulta preparada después de obtener los resultados

                    $Correousuario_asignado->close();

                    if ($usuarios_rut_user !== 'comodin_null') {
                        $tipo_historico = "correo_enviado";

                        $horaActual = date('Y-m-d H:i:s');
                        $nuevaHoraActual = date('Y-m-d H:i:s', strtotime($horaActual) + 2);

                        $historico_correo_asignacion_enviado = $conexion->prepare("INSERT INTO historico (fecha_hora, tipo_historico, rut_empresa, rut_user, id_ticket, estado_actual, rut_usuario_asignado,correo_asignado,correo_asunto,body) VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?)");
                        $historico_correo_asignacion_enviado->bind_param("sssssissss", $nuevaHoraActual, $tipo_historico, $rut_empresa, $rut_user_generador, $id_ticket, $estado_tk, $usuarios_rut_user, $correo_user_asignado, $GM_Subject, $GM_Body);

                        if ($historico_correo_asignacion_enviado->execute()) {
                            $historico_correo_asignacion_enviado->close();

                            // Cerrar consulta preparada
                        } else {
                            echo "Error al ejecutar la consulta preparada: " . $historico_correo_asignacion_enviado->error;
                        }
                    } else {
                    }
                } else {
                    // Manejo de errores si la ejecución falla
                    echo "Error al ejecutar la consulta preparada: " . $stmtInsertHistorico_asignado->error;
                }
            }




            // echo "Archivo  insertado con éxito.";
        } else {
            echo "Error al insertar en la base de datos: " . $conexion->error;
        }
    } else {
        // Si hubo un error en la inserción, muestra un mensaje de error
        echo "Error al crear el ticket: " . $conexion->error;
    }

    // Cierra la conexión a la base de datos






    $idTicket = $id_ticket;

    // Iniciar una transacción para garantizar la coherencia de la base de datos
    $conexion->begin_transaction();

    try {
        // Consultar archivos temporales asociados al id_ticket
        $sql = "SELECT nombre_archivo, nombre_temporal, sector FROM archivos_temporales WHERE id_ticket = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $idTicket);
        $stmt->execute();
        $result = $stmt->get_result();

        // Preparar consulta para insertar en archivo_adjunto
        $stmtInsert = $conexion->prepare("INSERT INTO archivo_adjunto (nombre_archivo, ruta_archivo, id_ticket, fecha_subida, sector, nombre_gcp) VALUES (?, ?, ?, ?, ?, ?)");

        while ($row = $result->fetch_assoc()) {
            $nombreOriginal = $row['nombre_archivo'];
            $nombreTemporal = $row['nombre_temporal'];
            $sector = $row['sector'];

            // Subir el archivo a Google Cloud Storage
            $archivoLocal = '../temp/' . $nombreTemporal;
            $nombreRemoto = 'Adjuntos_ticket/' . uniqid() . '_' . $nombreOriginal;
            $nombreGCP = substr($nombreRemoto, 16);

            // Verificar si el archivo existe y es legible antes de proceder
            if (file_exists($archivoLocal) && is_readable($archivoLocal)) {
                // Intentar subir el archivo a Google Cloud Storage
                try {
                    $bucket->upload(
                        fopen($archivoLocal, 'r'),
                        ['name' => $nombreRemoto]
                    );
                } catch (Exception $e) {
                    echo "Ocurrió un error al subir el archivo: " . $e->getMessage();
                    // Considerar si desea continuar o detener el script aquí
                }

                // Actualizar la tabla archivo_adjunto
                $horaActual = date('Y-m-d H:i:s');
                $stmtInsert = $conexion->prepare("INSERT INTO archivo_adjunto (nombre_archivo, ruta_archivo, id_ticket, fecha_subida, sector, nombre_gcp) VALUES (?, ?, ?, ?, ?, ?)");
                $stmtInsert->bind_param("ssssss", $nombreOriginal, $nombreRemoto, $idTicket, $horaActual, $sector, $nombreGCP);

                $tipo_historico = "adjunto";

                $stmtInsertHistorico_adjunto = $conexion->prepare("INSERT INTO historico ( fecha_hora,tipo_historico,rut_empresa, rut_user ,id_ticket,estado_actual,rut_usuario_asignado,adjuntos,nombre_gcp) VALUES (?, ?, ?,?,?,?,?,?,?)");
                $stmtInsertHistorico_adjunto->bind_param("sssssisss", $nuevaHoraActual, $tipo_historico, $rut_empresa, $rut_user_generador, $id_ticket, $estado_tk, $usuarios_rut_user, $nombreOriginal, $nombreGCP);
                $stmtInsertHistorico_adjunto->execute();

                if ($stmtInsert->execute()) {
                    // echo "Archivo $nombreOriginal insertado con éxito.";
                } else {
                    echo "Error al insertar en la base de datos: " . $conexion->error;
                }

                // Intentar eliminar el archivo temporal del servidor
                if (!unlink($archivoLocal)) {
                    echo "Error al eliminar el archivo temporal: $archivoLocal";
                }
            } else {
                echo "No se puede acceder a $archivoLocal. Por favor, verifica la ruta y los permisos.";
            }
        }

        // Eliminar los registros de archivos temporales de la tabla archivos_temporales
        $stmtDelete = $conexion->prepare("DELETE FROM archivos_temporales WHERE id_ticket = ?");
        $stmtDelete->bind_param("s", $idTicket);
        $stmtDelete->execute();





        // Si todo salió bien, hacer commit a la transacción
        $conexion->commit();
    } catch (Exception $e) {
        // Manejo de errores si algo sale mal en la transacción
        // No es necesario cerrar las consultas preparadas aquí

        // Hacer rollback a la transacción
        $conexion->rollback();

        // Registrar el error
        error_log("Error en la carga de archivos y actualización de la base de datos: " . $e->getMessage());
        // Considera mostrar un mensaje al usuario o realizar alguna acción adicional
    }


















    $frond_estado = 'Nuevo';


    $query_datos_cliente = "SELECT nombre, SUBSTRING_INDEX(apellidos, ' ', 1) AS primer_apellido, correo from cliente WHERE rut_cliente = '$rut_cliente'";
    $result_datos_cliente = $conexion->query($query_datos_cliente);

    if ($result_datos_cliente) {
        $row = $result_datos_cliente->fetch_assoc();

        $result_datos_cliente_nombre = $row['nombre'];
        $result_datos_cliente_primer_apellido = $row['primer_apellido'];
        $result_datos_cliente_primer_correo = $row['correo'];


        //Variables Globales Para crear mail 
        $GM_addEmbeddedImagen_ruta = '../images/logonav.png'; // ruta de imagen firma
        $GM_addEmbeddedImagen_nombre = 'logo_mail_acis'; // nombre de embebida imagen firma

        $GM_addEmbeddedImagen_ruta2 = '../images/footer_mail_ticket.png'; // ruta de imagen firma
        $GM_addEmbeddedImagen_nombre2 = 'logo_mail_acis'; // nombre de embebida imagen firma

        $GM_addAdress_Correo = $result_datos_cliente_primer_correo; // Cambia esto al destinatario real
        $GM_addAdress_nombre = $result_datos_cliente_nombre; // Cambia esto al destinatario real
        $GM_addAdress_ap_paterno = $result_datos_cliente_primer_apellido; // Cambia esto al destinatario real
        $GM_Subject = $resumen; //asunto del mail
        //$GM_enlace_verificacion = $enlace_verificacion; //enlace de verificacion
        // cuerpo del correo
        $GM_Body = '
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Correo de Ejemplo</title>
        </head>
        <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
        
            <table width="80%" border="1" cellspacing="0" cellpadding="0" style="width: 80%; border-collapse: collapse; margin: auto; margin-top: 50px;">
                <tr>
                    <td style=" text-align: center;">
                    <img src="cid:' . $GM_addEmbeddedImagen_nombre . '" class="logonav" style="width: 200px; margin: 0; padding: 0;">
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px;">
                       
                        <p>Estimad@, ' . $GM_addAdress_nombre . ' ' . $GM_addAdress_ap_paterno . '</p>
                        <p>Junto con saludar, informamos que se ha generado correctamente su requerimiento N° ' . $id_ticket . '</p>
                        <p>Detalles del Requerimiento:</p>
                        <ul>
                            <li>Abierto Por: ' . $nombre_creador . '</li>
                            <li>Solicitado a Nombre de: Gabriel Aravena Briones</li>
                            <li>Estado: ' . $frond_estado . '</li>
                            <li>Resumen:
                                <ul>
                                    <li>Detalle solicitud = ' . $descripcion . '</li>
                                </ul>
                            </li>
                        </ul>
                        <p>Favor no responda este mensaje. En caso de observaciones o consulta respecto a su requerimiento, comuníquese con el responsable a través de los canales de Atención.</p>
                        <p>Puede ver todos los detalles del requerimiento siguiendo el enlace que se incluye a continuación:</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px; background-color: #f4f4f4; text-align: center;">
                        <img src="cid:' . $GM_addEmbeddedImagen_nombre2 . '" class="footer_mail" style="display: block; margin-left: auto; margin-right: auto;">
                    </td>
                </tr>
            </table>
        
        </body>
        </html>';

        $tipo_historico = "correo_enviado_solicitante";

        $horaActual = date('Y-m-d H:i:s');
        $nuevaHoraActual = date('Y-m-d H:i:s', strtotime($horaActual) + 2);

        $historico_correo_solicitante_enviado = $conexion->prepare("INSERT INTO historico (fecha_hora, tipo_historico, rut_empresa, rut_user, id_ticket, estado_actual, rut_usuario_asignado,correo_asignado,correo_asunto,correo_solicitante,body) VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?)");
        $historico_correo_solicitante_enviado->bind_param("sssssisssss", $nuevaHoraActual, $tipo_historico, $rut_empresa, $rut_user_generador, $id_ticket, $estado_tk, $usuarios_rut_user, $correo_user_asignado, $GM_Subject, $result_datos_cliente_primer_correo, $GM_Body);

        if ($historico_correo_solicitante_enviado->execute()) {
            $historico_correo_solicitante_enviado->close();

            // Cerrar consulta preparada
        } else {
            echo "Error al ejecutar la consulta preparada: " . $historico_correo_solicitante_enviado->error;
        }

        include('controlador_smtp.php');
    } else {
        // Manejo de errores si la consulta falla
        echo "Error en la consulta: " . $conexion->error;
        exit(); // Termina el script si hay un error
    }

    //echo '<script>enviarIdTicketAlControlador("' . $id_ticket . '");</script>';
    echo '<script>window.location.href = "../views/inicio.php";</script>';
}
