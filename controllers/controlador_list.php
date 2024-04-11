<?php
// controlador_list.php

// Tu código de conexión a la base de datos aquí (ya proporcionado en tu pregunta anterior)
include("../config/conexion_bd.php");

// Inicializa las variables de filtro con valores predeterminados o vacíos
$filtro_cliente = "";
$filtro_numero_ticket = "";
$filtro_estado = ""; // Agrega la variable de filtro de estado
$filtro_asignado = "";
$filtro_SIN_asignar = "";

// Verifica si se han recibido las variables por GET
if (isset($_GET["filtro_cliente"])) {
    $filtro_cliente = $_GET["filtro_cliente"];
}

if (isset($_GET["filtro_numero_ticket"])) {
    $filtro_numero_ticket = $_GET["filtro_numero_ticket"];
}



if (isset($_GET["TnVldm8"])) { // Verifica si se proporciona la variable de filtro de estado en la URL
    $filtro_SIN_asignar = "sin asignar";
}

if (isset($_GET["RW4gUHJvZ3Jlc28"])) { // Verifica si se proporciona la variable de filtro de estado en la URL
    $filtro_estado = "En Progreso";
}

if (isset($_GET["UGVuZGllbnRl"])) { // Verifica si se proporciona la variable de filtro de estado en la URL
    $filtro_estado = "Pendiente";
}

if (isset($_GET["UmVzdWVsdG8"])) { // Verifica si se proporciona la variable de filtro de estado en la URL
    $filtro_estado = "Resuelto";
}


if (isset($_GET["YXNpZ25hZG8"])) { // Verifica si se proporciona la variable de filtro de estado en la URL
    $filtro_asignado = base64_decode($_GET["YXNpZ25hZG8"]);
}

// Consulta SQL para seleccionar registros de la tabla "ticket" con filtros y obtener nombres en lugar de RUTs e IDs en lugar de nombres
$sql = "SELECT t.id_ticket as numero, t.resumen, t.descripcion, t.fecha_creacion, CONCAT(u.nombre , ' ', u. ap_paterno ) as abierto_por, est.nombre as nombre_estado,
e.nombre AS nombre_empresa, CONCAT(c.nombre, ' ', c.apellidos) AS nombre_cliente, COALESCE(CONCAT(ua.nombre , ' ', ua.ap_paterno ), 'Sin Asignar') AS nombre_usuario,
p.nombre AS nombre_prioridad, est.nombre AS nombre_estado
FROM ticket t
LEFT JOIN empresa e ON t.rut_empresa = e.rut_empresa
LEFT JOIN cliente c ON t.cliente_rut_cliente = c.rut_cliente
LEFT JOIN usuarios u ON t.rut_user_generador = u.rut_user
LEFT JOIN usuarios ua ON t.usuarios_rut_user = ua.rut_user
LEFT JOIN PRIORIDAD p ON t.id_prioridad = p.id_prioridad
LEFT JOIN estado_ticket est ON t.id_estado_tk = est.id_estado_tk
WHERE 1=1";

// Agrega condiciones a la consulta según los valores recibidos
if (!empty($filtro_cliente)) {
    $sql .= " AND t.cliente_rut_cliente = '$filtro_cliente'";
}

if (!empty($filtro_numero_ticket)) {
    $sql .= " AND t.id_ticket = '$filtro_numero_ticket' ";
}

// Agrega la condición para el filtro de estado solo si se proporciona en la URL
if (!empty($filtro_estado)) {
    $sql .= " AND est.nombre = '$filtro_estado'";
}

// Agrega la condición para el filtro de estado solo si se proporciona en la URL
if (!empty($filtro_asignado)) {
    $sql .= " AND ua.rut_user = '$filtro_asignado' AND est.nombre != 'Resuelto'";
}

// Agrega la condición para el filtro de estado solo si se proporciona en la URL
if (!empty($filtro_SIN_asignar)) {
    $sql .= " AND ua.rut_user  = 'comodin_null' ";
}


// Agrega el ordenamiento al final de la consulta
$sql .= " ORDER BY t.fecha_creacion DESC";

// Ejecutar la consulta
$resultado = $conexion->query($sql);

// Cierra la conexión a la base de datos
$conexion->close();
