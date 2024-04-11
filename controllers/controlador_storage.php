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

include("../config/conexion_bd.php");

// Obtener el id_ticket desde la solicitud POST
if (isset($_POST['id_ticket'])) {
    $idTicket = $_POST['id_ticket'];
    echo 'el ticket storage: ' . $idTicket;
    // Consultar archivos temporales asociados al id_ticket
    $sql = "SELECT nombre_archivo, nombre_temporal,sector FROM archivos_temporales WHERE id_ticket = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $idTicket);
    $stmt->execute();
    $result = $stmt->get_result();

      // Verificar si el archivo existe y es legible antes de proceder
      if (file_exists($archivoLocal) ) {
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

    // Eliminar los registros de archivos temporales de la tabla archivos_temporales
    $stmtDelete = $conexion->prepare("DELETE FROM archivos_temporales WHERE id_ticket = ?");
    $stmtDelete->bind_param("s", $idTicket);
    $stmtDelete->execute();

    echo 'Archivos subidos a Google Cloud Storage y relacionados con el ticket correctamente.';
   
} else {
    echo 'ID de ticket no proporcionado.' . $idTicket;
}
echo '<script>window.location.href = "../views/inicio.php";</script>';

?>