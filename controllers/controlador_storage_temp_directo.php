<?php
include("../config/conexion_bd.php");

$response = array();

// Verifica si se ha enviado un archivo
if (isset($_FILES['archivoTemporal'])) {
    $archivo = $_FILES['archivoTemporal'];

    // Obtén el valor del campo 'numero' enviado en la solicitud
    $numero = $_POST['numero'];

    // Define la carpeta temporal donde se guardarán los archivos (ajusta la ruta según tu estructura de carpetas)
    $carpetaTemporal = '../temp/';

    // Genera un nombre único para el archivo temporal
    $nombreArchivoTemporal = uniqid() . '_' . $archivo['name'];

    // Mueve el archivo a la carpeta temporal
    if (move_uploaded_file($archivo['tmp_name'], $carpetaTemporal . $nombreArchivoTemporal)) {
        // Inserta información sobre el archivo temporal en la base de datos
        $nombreOriginal = $archivo['name'];
        $fechaSubida = date('Y-m-d H:i:s');
        $idTicket = $numero; // Aquí debes establecer el ID del ticket al que pertenece el archivo temporal, si es aplicable
        $usuarioSubida = 'nombre_usuario'; // Aquí debes establecer el nombre del usuario que subió el archivo, si es aplicable
        $sector = 'Update';

        // Realiza la inserción en la tabla de archivos temporales
        require_once '../config/conexion_bd.php'; // Asegúrate de tener acceso a la conexión de la base de datos

        $sql = "INSERT INTO archivos_temporales (nombre_archivo, nombre_temporal, fecha_subida, id_ticket, usuario_subida,sector) VALUES (?, ?, ?, ?, ?,?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ssssss', $nombreOriginal, $nombreArchivoTemporal, $fechaSubida, $idTicket, $usuarioSubida,$sector);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['nombreArchivo'] = $nombreArchivoTemporal;
        } else {
            $response['success'] = false;
            $response['mensaje'] = 'Error al subir el archivo o registrar en la base de datos: ' . $stmt->error;
        }
    } else {
        $response['success'] = false;
        $response['mensaje'] = 'Error al subir el archivo.';
    }
} else {
    //$response['success'] = false;
    //$response['mensaje'] = 'No se ha enviado ningún archivo o se ha producido un error en la subida.';
}

// Envía la respuesta como JSON
echo json_encode($response);
?>
