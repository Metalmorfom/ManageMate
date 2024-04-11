<?php
// Incluir el archivo de configuración de GCP Storage
require '../vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;

// Configuración de Google Cloud Storage
$projectId = 'tiketeraacis';
$bucketName = 'repositorioacis';
$keyFilePath = '../tiketeraacis-082c54670f64.json';

// Crear una instancia de StorageClient con la configuración
$storage = new StorageClient([
    'projectId' => $projectId,
    'keyFilePath' => $keyFilePath
]);

// Obtener el bucket usando el StorageClient
$bucket = $storage->bucket($bucketName);

// Obtener el nombre de archivo desde la solicitud POST
$nombreArchivo = $_POST['nombreArchivo'];

// Lógica para consultar GCP Storage y obtener la ruta de descarga
try {
    // Referencia al objeto en GCP Storage
    $object = $bucket->object("Adjuntos_ticket/$nombreArchivo");
    
    // Especifica opciones para la URL firmada, incluyendo el encabezado 'Content-Disposition'
    $options = [
        'responseDisposition' => 'attachment; filename="' . basename($nombreArchivo) . '"',
        'expiry' => new \DateTime('2030-01-01T00:00:00Z')
    ];
    
    // Generar la URL firmada con las opciones adicionales
    $url = $object->signedUrl($options['expiry'], $options);
    
    // Crear una respuesta JSON con la ruta de descarga
    $response = array(
        'success' => true,
        'rutaDescarga' => $url
    );
    
    // Devolver la respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (\Exception $e) {
    // Manejar errores según tus necesidades
    $response = array(
        'success' => false,
        'message' => 'Error al obtener la ruta desde GCP: ' . $e->getMessage()
    );
    
    // Devolver la respuesta JSON de error
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
