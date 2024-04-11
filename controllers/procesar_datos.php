<?php
// procesar_datos.php

// Incluir el archivo de conexión a la base de datos
include("../config/conexion_bd.php");

// Verifica si se han recibido los datos POST
if(isset($_POST['id_ticket']) && isset($_POST['usuario']) && isset($_POST['Notas_del_Trabajo'])) {
    
    $hora_actual = date('Y-m-d H:i:s');
    // Obtiene los datos desde la solicitud AJAX
    $id_ticket = $_POST['id_ticket'];
    $usuario = $_POST['usuario'];
    $notas_del_trabajo = $_POST['Notas_del_Trabajo'];

    // Consulta SQL para insertar los datos en la tabla 'notas_trab'
    $sql_insertar_notas = "INSERT INTO notas_trab (fecha_hora, descripcion, rut_user, id_ticket) VALUES (?, ?, ?, ?)";
    $stmt_insertar_notas = $conexion->prepare($sql_insertar_notas);
    $stmt_insertar_notas->bind_param("ssss", $hora_actual, $notas_del_trabajo, $usuario, $id_ticket);

    // Ejecutar la consulta
    if ($stmt_insertar_notas->execute()) {
        // Si la inserción fue exitosa, envía una respuesta de éxito al cliente
        echo "Datos recibidos y procesados con éxito.";
        
        
      
        
    } else {
        // Si hubo un error en la inserción, envía una respuesta de error
        echo "Error: No se pudieron insertar los datos en la base de datos.";
    }

    // Cierra la conexión a la base de datos
    $stmt_insertar_notas->close();
    $conexion->close();
} else {
    // Si no se reciben los datos esperados, envía una respuesta de error
    echo "Error: No se han recibido los datos esperados.";
}
?>
