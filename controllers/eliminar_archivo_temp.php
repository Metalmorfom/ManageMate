<?php

//eliminar_archivo_temp.php

if (isset($_POST['nombreArchivo'])) {
    $nombreArchivo = $_POST['nombreArchivo'];
    $rutaArchivo = '../temp/' . $nombreArchivo;

    // Verificar si el archivo existe antes de intentar eliminarlo
    if (file_exists($rutaArchivo)) {
        if (unlink($rutaArchivo)) {
            // Eliminación exitosa del archivo en el servidor PHP

            // Ahora, eliminemos el registro de la tabla archivos_temporales
            require_once '../config/conexion_bd.php'; // Asegúrate de tener acceso a la conexión de la base de datos

            $sql = "DELETE FROM archivos_temporales WHERE nombre_temporal = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('s', $nombreArchivo);

            if ($stmt->execute()) {
                // Eliminación exitosa del registro en la tabla archivos_temporales
                echo 'OK';
            } else {
                // Error al eliminar el registro en la tabla archivos_temporales
                echo 'Error al eliminar el registro en la tabla archivos_temporales: ' . $stmt->error;
            }
        } else {
            // Error al eliminar el archivo en el servidor
            echo 'Error al eliminar el archivo en el servidor..... Nombre del archivo: ' . $nombreArchivo . ';';

        }
    } else {
        // El archivo no existe
        echo 'El archivo no existe en el servidor.';
    }
} else {
    // Nombre de archivo no proporcionado en la solicitud
    echo 'Nombre de archivo no proporcionado.';
}
?>
