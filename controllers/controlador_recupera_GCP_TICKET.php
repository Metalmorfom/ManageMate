<?php
//controlador_recupera_GCP_TICKET.PHP

// Incluir el archivo de conexión a la base de datos
include("../config/conexion_bd.php");

// Verificar si se ha recibido el ID del ticket por GET
if (isset($_GET["id_ticket"])) {
    $idTicket = $_GET["id_ticket"];

    // Preparar la consulta SQL para obtener los nombres y rutas de archivo
    $sql = "SELECT nombre_gcp, ruta_archivo FROM archivo_adjunto WHERE id_ticket = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $idTicket);
    $stmt->execute();
    $result = $stmt->get_result();

    $archivos = array(); // Array para almacenar los nombres y rutas de archivo

    while ($row = $result->fetch_assoc()) {
        $nombreArchivo = $row['nombre_gcp'];
        $rutaArchivo = $row['ruta_archivo'];
        
        

        // Agregar los datos al array
        $archivos[] = array(
            'nombreArchivo' => $nombreArchivo,
            'rutaArchivo' => $rutaArchivo
        );
    }

    // Crear un arreglo de respuesta JSON
    $response = array(
        'success' => true,
        'archivos' => $archivos
    );

    // Devolver la respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // En caso de que no se reciba el ID del ticket, devolver un mensaje de error
    $response = array(
        'success' => false,
        'message' => 'ID del ticket no proporcionado.'
    );

    // Devolver la respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
