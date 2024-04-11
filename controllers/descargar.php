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

// Listar objetos en el bucket
$objects = $bucket->objects();

echo '<ul>';
foreach ($objects as $object) {
    echo '<li><a href="descargar.php?archivo=' . urlencode($object->name()) . '">' . $object->name() . '</a></li>';
}
echo '</ul>';
