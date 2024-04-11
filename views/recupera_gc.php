<?php
require 'vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

// Configuración de Google Cloud Storage
$projectId = 'tiketeraacis';
$bucketName = 'repositorioacis';
$keyFilePath = 'tiketeraacis-082c54670f64.json';

$storage = new StorageClient([
    'projectId' => $projectId,
    'keyFilePath' => $keyFilePath
]);

$bucket = $storage->bucket($bucketName);

// Ruta del archivo en Google Cloud Storage
$archivoRemoto = 'Adjuntos_ticket/654ec6d66bca7_Screenshot_1.png'; // Reemplaza con el nombre del archivo que deseas recuperar

if ($bucket->object($archivoRemoto)->exists()) {
    // Establece las cabeceras para la descarga del archivo
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($archivoRemoto) . '"');

    // Abre el archivo y lo muestra en el flujo de salida
    $object = $bucket->object($archivoRemoto);
    echo $object->downloadAsString(); // O utiliza downloadAsStream() para grandes archivos

    exit;
} else {
    echo 'El archivo no se encuentra en el almacenamiento.';
}
?>
