<?php
// load_notas.php

// Incluir el archivo de conexión a la base de datos
include("../config/conexion_bd.php");

// Verificar si se proporcionó el número de ticket tanto en GET como en POST
if (isset($_REQUEST['filtro_numero_ticket']) && !empty($_REQUEST['filtro_numero_ticket'])) {
    $id_ticket_encoded = $_REQUEST['filtro_numero_ticket'];
    $id_ticket = base64_decode($id_ticket_encoded);

    // Llama a la función para cargar las notas de trabajo
    $combined_results = cargarNotasTrabajo($id_ticket);

   

    // Ahora puedes recorrer $combined_results y mostrar los resultados ordenados por fecha en tu página web
}




function cargarNotasTrabajo($id_ticket)
{
    // Incluir el archivo de conexión a la base de datos
    include("../config/conexion_bd.php");
    // Consulta SQL para obtener las notas de trabajo ordenadas por fecha y combinarlas con los datos de usuario
    $sql_notas = "SELECT n.*, CONCAT(u.Nombre, ' ', u.ap_paterno, ' ', u.ap_materno) AS nombre_usuario_notas FROM notas_trab n LEFT JOIN usuarios u ON n.rut_user = u.rut_user WHERE n.id_ticket = ? ORDER BY n.fecha_hora DESC";


    $stmt_notas = $conexion->prepare($sql_notas);
    $stmt_notas->bind_param("s", $id_ticket);
    $stmt_notas->execute();
    $result_notas = $stmt_notas->get_result();

    // Consulta SQL para obtener el historial ordenado por fecha
    $sql_historico = "SELECT
                h.*,
                CONCAT(ua.Nombre, ' ', ua.ap_paterno, ' ', ua.ap_materno) AS nombre_usuario_asignado,
                CONCAT(u.Nombre, ' ', u.ap_paterno, ' ', u.ap_materno) AS nombre_usuario_historico,
                us.nombre  AS nombre_estado_actual,
                uss.nombre  AS nombre_estado_anterior,
                adjuntos as archivos_adjuntos,nombre_GCP,correo_asunto,correo_asignado,correo_solicitante,body

            FROM
                historico h
            LEFT JOIN
                usuarios ua ON h.rut_usuario_asignado = ua.rut_user
            LEFT JOIN
                usuarios u ON h.rut_user = u.rut_user
            LEFT JOIN
            estado_ticket us ON  h.estado_actual  = us.id_estado_tk
            LEFT JOIN
            estado_ticket uss ON  h.estado_anterior  = uss.id_estado_tk
            WHERE
                id_ticket = ?
            ORDER BY
                fecha_hora DESC;";

    $stmt_historico = $conexion->prepare($sql_historico);
    $stmt_historico->bind_param("s", $id_ticket);
    $stmt_historico->execute();
    $result_historico = $stmt_historico->get_result();

    // Array para almacenar los resultados combinados y ordenados por fecha
    $combined_results = array();

    // Obtener resultados de notas de trabajo y agregarlos al array combinado
    while ($fila_notas = $result_notas->fetch_assoc()) {
        $combined_results[] = $fila_notas;
    }

    // Obtener resultados del historial y agregarlos al array combinado
    while ($fila_historico = $result_historico->fetch_assoc()) {
        $combined_results[] = $fila_historico;
    }

    // Ordenar el array combinado por fecha
    usort($combined_results, function ($a, $b) {
        return strtotime($b['fecha_hora']) - strtotime($a['fecha_hora']);
    });


   // echo '<pre>';
    //print_r($combined_results);
    //echo '</pre>';



    // Devolver el resultado combinado
    return $combined_results;
}
