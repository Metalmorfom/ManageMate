<?php
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

// Obtén una lista de objetos en el bucket
$objects = $bucket->objects();

if (isset($_GET['file'])) {
    // Nombre del archivo a descargar
    $fileName = $_GET['file'];

    // Verifica que el archivo exista en el bucket
    $file = $bucket->object($fileName);

    if ($file->exists()) {
        // Configura las cabeceras para forzar la descarga del archivo
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        // Lee el contenido del archivo y envíalo al navegador
        echo $file->downloadAsString();
        exit;
    } else {
        echo 'El archivo no existe en el bucket.';
    }
}

// Muestra la lista de archivos y enlaces de descarga
echo '<h1>Archivos en el bucket:</h1>';
echo '<ul>';
foreach ($objects as $object) {
    $fileName = $object->name();
    echo '<li><a href="?file=' . $fileName . '">' . $fileName . '</a></li>';
}
echo '</ul>';
?>
