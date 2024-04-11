<?php

// eliminar_archivo_adjunto.php
require_once '../config/conexion_bd.php'; // Asegúrate de tener acceso a la conexión de la base de datos
require '../vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;

// Configuración de Google Cloud Storage
$projectId = 'tiketeraacis';
$bucketName = 'repositorioacis';
$keyFilePath = '../tiketeraacis-082c54670f64.json';

if (isset($_POST['nombreArchivo'])) {
    $nombreArchivo = $_POST['nombreArchivo'];
    $rutaArchivo = '../temp/' . $nombreArchivo;

    // Verificar si el archivo existe en el servidor local
    if (file_exists($rutaArchivo)) {
        // Eliminar el archivo del servidor local
        if (unlink($rutaArchivo)) {
            // Eliminación exitosa del archivo en el servidor local

            // Ahora, eliminemos el registro de la tabla archivos_temporales
            

            $sql = "DELETE FROM archivos_temporales WHERE nombre_temporal = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('s', $nombreArchivo);

            if ($stmt->execute()) {
                // Eliminación exitosa del registro en la tabla archivos_temporales

                // Eliminar el registro de la tabla de archivos adjuntos
                $sql = "DELETE FROM archivo_adjunto WHERE nombre_gcp = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('s', $nombreArchivo);

                if ($stmt->execute()) {
                    // Eliminación exitosa del registro en la tabla archivos_adjuntos
                    echo 'OK';
                } else {
                    // Error al eliminar el registro en la tabla archivos_adjuntos
                    echo 'Error al eliminar el registro en la tabla archivos_adjuntos: ' . $stmt->error;
                }
            } else {
                // Error al eliminar el registro en la tabla archivos_temporales
                echo 'Error al eliminar el registro en la tabla archivos_temporales: ' . $stmt->error;
            }
        } else {
            // Error al eliminar el archivo en el servidor local
            echo 'Error al eliminar el archivo en el servidor local: ' . $nombreArchivo;
        }
    } else {
        // El archivo no existe en el servidor local, intentemos eliminarlo de GCP

        // Conexión a Google Cloud Storage
        $storage = new StorageClient([
            'projectId' => $projectId,
            'keyFilePath' => $keyFilePath
        ]);
        $bucket = $storage->bucket($bucketName);
        $nombreGCP = 'Adjuntos_ticket/' . $nombreArchivo; // Ajusta según la estructura de tus archivos en GCP

        $object = $bucket->object($nombreGCP);

        if ($object->exists()) {
            // Eliminar el archivo de GCP
            $object->delete();

            // Eliminar el registro de la tabla de archivos adjuntos
            $sql = "DELETE FROM archivo_adjunto WHERE nombre_gcp = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('s', $nombreArchivo);

            if ($stmt->execute()) {
                // Eliminación exitosa del registro en la tabla archivos_adjuntos
                echo 'OK';
            } else {
                // Error al eliminar el registro en la tabla archivos_adjuntos
                echo 'Error al eliminar el registro en la tabla archivos_adjuntos: ' . $stmt->error;
            }
        } else {
            // El archivo no existe en GCP
            echo 'Error: El archivo no existe en GCP.';
        }
    }
} else {
    // Nombre de archivo no proporcionado en la solicitud
    echo 'Nombre de archivo no proporcionado.';
}
?>
