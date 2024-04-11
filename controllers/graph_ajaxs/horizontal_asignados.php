<?php
include("../../config/conexion_bd.php");

// Obtiene los valores enviados en la petición
$usarFechas = isset($_GET['usarFechas']) ? filter_var($_GET['usarFechas'], FILTER_VALIDATE_BOOLEAN) : false;
$fechaInicial = isset($_GET['fechaInicial']) ? $_GET['fechaInicial'] : '';
$fechaTermino = isset($_GET['fechaTermino']) ? $_GET['fechaTermino'] : '';

// Comienza a construir la consulta SQL base
$sql = "SELECT * FROM vista_Horizontal_asignados";

// Si el checkbox está marcado, añade el filtro de fechas a la consulta
if ($usarFechas && $fechaInicial && $fechaTermino) {
    // Asegúrate de que las fechas sean válidas para evitar errores de SQL
    $fechaInicial = date('Y-m-d', strtotime($fechaInicial));
    $fechaTermino = date('Y-m-d', strtotime($fechaTermino));

    $sql .= " AND DATE(t.fecha_creacion) BETWEEN '$fechaInicial' AND '$fechaTermino'";
}

//CREATE OR REPLACE VIEW vista_Horizontal_asignados AS
//SELECT u.rut_user AS usuarios_rut_user, u.nombre AS nombre_usuario, COUNT(*) AS cantidad_tickets
//FROM ticket t
//INNER JOIN usuarios u ON t.usuarios_rut_user = u.rut_user
//WHERE t.id_estado_tk <> 4
//GROUP BY u.rut_user, u.nombre
//ORDER BY Cantidad_tickets desc

$result = mysqli_query($conexion, $sql);

if (!$result) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

$usuarios = [];
$cantidadTickets = [];

while ($row = mysqli_fetch_assoc($result)) {
    $usuarios[] = $row['nombre_usuario'];
    $cantidadTickets[] = $row['cantidad_tickets'];
}

$response = [
    'usuarios' => $usuarios,
    'cantidadTickets' => $cantidadTickets
];

header('Content-Type: application/json');
echo json_encode($response);
?>
