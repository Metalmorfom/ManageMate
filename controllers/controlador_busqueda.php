<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de conexión a la base de datos
include("../config/conexion_bd.php");

$usuarios_rut_user = "";
$filtro_cliente = "";
$filtro_numero_ticket = "";
// Verifica si se han recibido las variables por POST
if (isset($_GET["filtro_cliente"])) {
    $filtro_cliente_encoded = $_GET["filtro_cliente"];
    $filtro_cliente = base64_decode($filtro_cliente_encoded);
}

// Obtener el ID del ticket desde la URL
if (isset($_GET['filtro_numero_ticket']) && !empty($_GET['filtro_numero_ticket'])) {
    $filtro_numero_ticket_encoded = $_GET['filtro_numero_ticket'];
    $id_ticket = base64_decode($filtro_numero_ticket_encoded);

    // echo '<script>cargarArchivosAdjuntos("' . $id_ticket . '");</script>';

    // Preparar la consulta SQL
    if ($filtro_cliente === "Servicios Gestionados") {
        // Consulta para buscar por ticket sin filtro de cliente
        $query = $conexion->prepare("SELECT t.id_ticket as numero_encontrado, 
            t.resumen, 
            t.descripcion, 
            t.fecha_creacion, 
            t.rut_user_generador, 
            t.id_estado_tk, 
            t.rut_empresa, 
            t.cliente_rut_cliente, 
            t.usuarios_rut_user, 
            t.Rut_usuario_afectado, 
            t.modelo, 
            t.id_prioridad, 
            t.Nombre_user_completo_afectado, 
            t.Mandante_afectado, 
            t.Cargo_afectado, 
            t.interno, 
            t.lavado_equipo, 
            t.UPW, 
            t.PRB, 
            t.PRD, 
            t.QAS, 
            t.Creacion, 
            t.desvinculacion, 
            t.homologacion, 
            t.reseteo,
            CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente,
            em.nombre as nombre_empresa,
            CONCAT(us.nombre,' ', us.ap_paterno,' ', us.ap_materno) AS usuario_asignado,
            CONCAT(usg.nombre,' ', usg.ap_paterno,' ', usg.ap_materno) AS nombre_generador
            FROM ticket t
            LEFT JOIN cliente c ON t.cliente_rut_cliente = c.rut_cliente
            LEFT JOIN empresa em ON t.rut_empresa = em.rut_empresa
            LEFT JOIN usuarios us ON  t.usuarios_rut_user = us.rut_user
            LEFT JOIN usuarios usg ON  t.rut_user_generador = usg.rut_user
            WHERE t.id_ticket = ?");
        $query->bind_param("s", $id_ticket);
    } else {
        // Consulta para buscar por ticket con filtro de cliente
        $query = $conexion->prepare("SELECT t.id_ticket as numero_encontrado, 
            t.resumen, 
            t.descripcion, 
            t.fecha_creacion, 
            t.rut_user_generador, 
            t.id_estado_tk, 
            t.rut_empresa, 
            t.cliente_rut_cliente, 
            t.usuarios_rut_user, 
            t.Rut_usuario_afectado, 
            t.modelo, 
            t.id_prioridad, 
            t.Nombre_user_completo_afectado, 
            t.Mandante_afectado, 
            t.Cargo_afectado, 
            t.interno, 
            t.lavado_equipo, 
            t.UPW, 
            t.PRB, 
            t.PRD, 
            t.QAS, 
            t.Creacion, 
            t.desvinculacion, 
            t.homologacion, 
            t.reseteo,
            CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente,
            em.nombre as nombre_empresa,
            CONCAT(us.nombre,' ', us.ap_paterno,' ', us.ap_materno) AS usuario_asignado,
            CONCAT(usg.nombre,' ', usg.ap_paterno,' ', usg.ap_materno) AS nombre_generador


            FROM ticket t
            LEFT JOIN cliente c ON t.cliente_rut_cliente = c.rut_cliente
            LEFT JOIN empresa em ON t.rut_empresa = em.rut_empresa
            LEFT JOIN usuarios us ON  t.usuarios_rut_user = us.rut_user
            LEFT JOIN usuarios usg ON  t.rut_user_generador = usg.rut_user
            WHERE t.id_ticket = ? AND em.rut_empresa = ?");
        $query->bind_param("ss", $id_ticket, $filtro_cliente);
    }

    $query->execute();
    $result = $query->get_result();





    // Verificar si se encontró el ticket
    if ($row = $result->fetch_assoc()) {
        // Asignar los valores a las variables
        // Asignar los valores a las variables
        $ticketencontrado = $row['numero_encontrado'];
        $resumen = $row['resumen'];
        $descripcion = $row['descripcion'];
        $fecha_creacion = $row['fecha_creacion'];
        $rut_user_generador = $row['rut_user_generador'];
        $nombre_generador = $row['nombre_generador'];
        $id_estado_tk = $row['id_estado_tk'];
        $nombre_empresa = $row['nombre_empresa'];
        $rut_empresa = $row['rut_empresa'];

        $cliente_rut_cliente = $row['cliente_rut_cliente'];
        $nombre_cliente = $row['nombre_cliente'];


        if ($row['usuario_asignado'] !== 'Comodin Comodin Comodin') {
            $usuarios_rut_user = $row['usuario_asignado'];
        } else {
            $usuarios_rut_user = '';
        }

        $usuarios_rut_user_rut = $row['usuarios_rut_user'];





        $Rut_usuario_afectado = $row['Rut_usuario_afectado'];
        $modelo = $row['modelo'];
        $prioridad_del_ticket = $row['id_prioridad'];

        $Nombre_user_completo_afectado = $row['Nombre_user_completo_afectado'];
        $Mandante_afectado = $row['Mandante_afectado'];
        $Cargo_afectado = $row['Cargo_afectado'];
        $interno = $row['interno'] ? true : false;
        $lavado_equipo = $row['lavado_equipo'] ? true : false;
        $UPW = $row['UPW'] ? true : false;
        $PRB = $row['PRB'] ? true : false;
        $PRD = $row['PRD'] ? true : false;
        $QAS = $row['QAS'] ? true : false;
        $Creacion = $row['Creacion'] ? true : false;
        $desvinculacion = $row['desvinculacion'] ? true : false;
        $homologacion = $row['homologacion'] ? true : false;
        $reseteo = $row['reseteo'] ? true : false;
        // Continúa asignando valores para otros campos necesarios
        // Ejemplo: $fecha_creacion = $row['fecha_creacion'];


    } else {
        // Manejar el caso en que no se encuentre el ticket
        // Establecer el código de respuesta HTTP en 404
        http_response_code(404);


        echo '<script>window.location.href = "HTTP/1.0 404 Not Found";</script>';
    }


    // Obtener todas las prioridades de la tabla PRIORIDAD
    $query_prioridades = $conexion->query("SELECT * FROM PRIORIDAD");

    $query_estado = $conexion->query("SELECT * FROM estado_ticket");
    // Verificar si se encontraron prioridades




} else {
}









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





if (isset($_POST["form_enviado"])) {



    // Obtener los valores de los campos del formulario
    $id_ticket = $_POST['numero'];
    $resumen = $_POST['resumen'];
    $descripcion = $_POST['descripcion'];
    $estado_ticket = $_POST['estado_tk'];

    // Obtener el RUT de la empresa desde la base de datos
    $nombre_empresa = $_POST['empresa'];
    $consultaEmpresa = "SELECT rut_empresa FROM empresa WHERE nombre = ?";
    $stmt = $conexion->prepare($consultaEmpresa);
    $stmt->bind_param("s", $nombre_empresa);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró un resultado
    if ($result->num_rows > 0) {
        // Obtener el RUT de la empresa
        $row = $result->fetch_assoc();
        $rut_empresa = $row['rut_empresa'];
    } else {
        http_response_code(404);


        echo '<script>window.location.href = "HTTP/1.0 404 Not Found";</script>';
        // Manejar el caso en el que no se encontró la empresa
        echo "No se encontró la empresa: $nombre_empresa";
        // Puedes decidir cómo manejar esta situación
        // Puedes redirigir al usuario a una página de error o hacer otra acción
        // Por ahora, el flujo continuará, pero debes decidir cómo manejarlo adecuadamente
    }

    $cliente = $_POST['rut_cliente'];
    $asignado = (!empty($_POST['asignado'])) ? $_POST['asignado'] : 'Comodin Comodin Comodin';




    $Rut_usuario_afectado = $_POST['Rut_usuario_afectado'];
    $modelo = $_POST['Modelo'];
    $prioridad = $_POST['prioridad'];
    $Nombre_user_completo_afectado = $_POST['Nombre_user_completo_afectado'];
    $Mandante_afectado = $_POST['Mandante_afectado'];
    $Cargo_afectado = $_POST['Cargo_afectado'];
    $Notas_del_Trabajo = $_POST['Notas_del_Trabajo'];




    $interno = isset($_POST['actividades']['recepcion']['interno']);
    $lavado_equipo = isset($_POST['actividades']['recepcion']['lavadoEquipo']);

    $UPW = isset($_POST['actividades']['ambiente']['UPW']);
    $PRB = isset($_POST['actividades']['ambiente']['PRB']);
    $PRD = isset($_POST['actividades']['ambiente']['PRD']);
    $QAS = isset($_POST['actividades']['ambiente']['QAS']);


    $Creacion = isset($_POST['actividades']['accion']['Creacion']);
    $desvinculacion = isset($_POST['actividades']['accion']['desvinculacion']);
    $homologacion = isset($_POST['actividades']['accion']['homologacion']);
    $reseteo = isset($_POST['actividades']['accion']['preparacionDespacho']);




    try {
        // Verificar si los campos no están vacíos
        if (!empty($asignado)) {
            // Dividir el campo cliente en nombre y apellidos
            list($nombre_asignado, $ap_paterno, $ap_materno) = explode(' ', $asignado);

            // Consulta SQL para obtener el RUT del usuario generador desde la tabla 'usuarios'
            $consultar_asignado = "SELECT rut_user FROM usuarios WHERE nombre = ? AND ap_paterno = ? AND ap_materno = ?";

            // Preparar la consulta SQL
            $stmt = $conexion->prepare($consultar_asignado);

            // Asociar el parámetro con el valor del nombre del usuario generador
            $stmt->bind_param("sss", $nombre_asignado, $ap_paterno, $ap_materno);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado de la consulta
            $result = $stmt->get_result();

            // Verificar si se encontró un resultado
            if ($result->num_rows > 0) {
                // Obtener el RUT del usuario generador
                $row = $result->fetch_assoc();
                $rut_user_asignadoupdate = (!empty($row['rut_user'])) ? $row['rut_user'] : "";
            }
        }
    } catch (Exception $e) {
        // Manejar la excepción si ocurre algún error
        echo "Error: " . $e->getMessage();
    }



    // Consulta SQL para actualizar el ticket
    $updateQuery = "UPDATE ticket SET 
    resumen = ?,
    descripcion = ?,
    id_estado_tk = ?,
    rut_empresa = ?,
    cliente_rut_cliente = ?,
    usuarios_rut_user = ?,
    Rut_usuario_afectado = ?,
    modelo = ?,
    id_prioridad = ?,
    Nombre_user_completo_afectado = ?,
    Mandante_afectado = ?,
    Cargo_afectado = ?,

    interno = ?,
        lavado_equipo = ?,
        UPW = ?,
        PRB = ?,
        PRD = ?,
        QAS = ?,
        Creacion = ?,
        desvinculacion = ?,
        homologacion = ?,
        reseteo = ?


WHERE id_ticket = ?";

    // Preparar la consulta SQL
    $updateStmt = $conexion->prepare($updateQuery);

    // Asociar los parámetros con los valores
    $updateStmt->bind_param(
        "ssisssssssssiiiiiiiiiis",
        $resumen,
        $descripcion,
        $estado_ticket, // Usar el ID obtenido
        $rut_empresa, // Usar el RUT de la empresa obtenido
        $cliente, // Usar el RUT obtenido
        $rut_user_asignadoupdate, // Usar el RUT obtenido
        $Rut_usuario_afectado,
        $modelo,
        $prioridad,
        $Nombre_user_completo_afectado,
        $Mandante_afectado,
        $Cargo_afectado,

        $interno, // Convertir booleano a entero
        $lavado_equipo,
        $UPW,
        $PRB,
        $PRD,
        $QAS,
        $Creacion,
        $desvinculacion,
        $homologacion,
        $prepracion_despacho_envio,
        // Agrega aquí las variables para las actividades y otros campos
        $id_ticket
    );


    // Ejecutar la consulta de actualización
    if ($updateStmt->execute()) {

        if ($estado_ticket != $id_estado_tk) {

            $tipo_historico = "cambio";
            $horaActual = date('Y-m-d H:i:s');
            $nuevaHoraActual = date('Y-m-d H:i:s', strtotime($horaActual) + 2);
            $stmtInsertHistorico_adjunto = $conexion->prepare("INSERT INTO historico ( fecha_hora,tipo_historico,rut_empresa, rut_user ,id_ticket,estado_actual,estado_anterior,rut_usuario_asignado,adjuntos,nombre_gcp) VALUES (?, ?, ?,?,?,?,?,?,?,?)");
            $stmtInsertHistorico_adjunto->bind_param("sssssiisss", $nuevaHoraActual, $tipo_historico, $rut_empresa, $rut_user_generador, $id_ticket, $estado_ticket, $id_estado_tk, $usuarios_rut_user, $nombreOriginal, $nombreGCP);
            $stmtInsertHistorico_adjunto->execute();
            $stmtInsertHistorico_adjunto->close();
        } else {
        }

        if ($asignado !== $usuarios_rut_user && !empty($rut_user_asignadoupdate && $usuarios_rut_user !== 'comodin_null' && $rut_user_asignadoupdate !== 'comodin_null')) {
            $tipo_historico = "Asignación";

            $horaActual = date('Y-m-d H:i:s');
            $nuevaHoraActual = date('Y-m-d H:i:s', strtotime($horaActual) + 2);


            $stmtInsertHistorico_asignado = $conexion->prepare("INSERT INTO historico ( fecha_hora,tipo_historico,rut_empresa, rut_user ,id_ticket,estado_actual,rut_usuario_asignado) VALUES (?, ?, ?,?,?,?,?)");
            $stmtInsertHistorico_asignado->bind_param("sssssis", $nuevaHoraActual, $tipo_historico, $rut_empresa, $rut_user_generador, $id_ticket, $estado_tk, $rut_user_asignadoupdate);

            $stmtInsertHistorico_asignado->execute();
            $stmtInsertHistorico_asignado->close();





            $Correousuario_asignado = $conexion->prepare("SELECT correo, nombre, ap_paterno, ap_materno FROM usuarios WHERE rut_user = ?");
            $Correousuario_asignado->bind_param("s", $rut_user_asignadoupdate);




            $rut_empresa_enviada = $conexion->prepare("SELECT nombre FROM empresa WHERE rut_empresa = ?");
            $rut_empresa_enviada->bind_param("s", $rut_empresa);
            $rut_empresa_enviada->execute();
            // Vincula el resultado a las variables
            $rut_empresa_enviada->bind_result($nombre_empresa_enviada);
            // Obtiene el resultado
            $rut_empresa_enviada->fetch();

            $rut_empresa_enviada->close();
            

            
            $rut_cliente_enviada = $conexion->prepare("SELECT nombre , apellidos FROM cliente WHERE rut_cliente = ?");
            $rut_cliente_enviada->bind_param("s", $cliente);
            $rut_cliente_enviada->execute();
            // Vincula el resultado a las variables
            $rut_cliente_enviada->bind_result($nombre_cliente_enviada , $apellido_cliente_enviada);
            // Obtiene el resultado
            $rut_cliente_enviada->fetch();

            $rut_cliente_enviada->close();
            




            $frond_estado = 'Nuevo';


            $Correousuario_asignado->execute();

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
                        <li><span class="gray-verdana"><strong>Local:</strong> '. $nombre_empresa_enviada .'</span></li>
                        <li><span class="gray-verdana"><strong>Nombre del cliente:</strong>  ' . $nombre_cliente_enviada . ' ' . $apellido_cliente_enviada . '</span></li>
                        <li><span class="gray-verdana"><strong>Usuario asignado:</strong>  ' . $nombre . ' ' . $ap_paterno . ' </span></li>
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


            include('controlador_smtp.php');

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
        };













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

                    if ($stmtInsert->execute() === TRUE) {

                        $tipo_historico = "adjunto";
                        $horaActual = date('Y-m-d H:i:s');
                        $nuevaHoraActual = date('Y-m-d H:i:s', strtotime($horaActual) + 2);
                        $stmtInsertHistorico_adjunto = $conexion->prepare("INSERT INTO historico ( fecha_hora,tipo_historico,rut_empresa, rut_user ,id_ticket,estado_actual,rut_usuario_asignado,adjuntos,nombre_gcp) VALUES (?, ?, ?,?,?,?,?,?,?)");
                        $stmtInsertHistorico_adjunto->bind_param("sssssisss", $nuevaHoraActual, $tipo_historico, $rut_empresa, $rut_user_generador, $id_ticket, $estado_tk, $usuarios_rut_user, $nombreOriginal, $nombreGCP);
                        $stmtInsertHistorico_adjunto->execute();
                        $stmtInsertHistorico_adjunto->close();

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
            // Si algo sale mal, hacer rollback a la transacción
            $conexion->rollback();

            // Registrar el error
            error_log("Error en la carga de archivos y actualización de la base de datos: " . $e->getMessage());
            // Considera mostrar un mensaje al usuario o realizar alguna acción adicional
        }

        // Cerrar las declaraciones preparadas y la conexión a la base de datos
        $stmt->close();
        $stmtInsert->close();
        $stmtDelete->close();
        $conexion->close();


        //echo '<script>window.location.href = "../views/inicio.php";</script>';
    } else {
        $mensaje_alerta = "Error al actualizar la información del ticket: " . $conexion->error;
    }

    echo '<script>window.location.href = "../views/inicio.php";</script>';
}
