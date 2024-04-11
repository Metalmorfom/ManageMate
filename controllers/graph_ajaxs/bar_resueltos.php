<?php
include("../../config/conexion_bd.php");

// Sanitize and validate input
$usarFechasResolved = filter_var($_GET['usarFechas'], FILTER_VALIDATE_BOOLEAN);
$fechaInicialResolved = $_GET['fechaInicial'];
$fechaTerminoResolved = $_GET['fechaTermino'];

// Validate date format
$fechaInicialIsValid = DateTime::createFromFormat('Y-m-d', $fechaInicialResolved) !== false;
$fechaTerminoIsValid = DateTime::createFromFormat('Y-m-d', $fechaTerminoResolved) !== false;

if ($usarFechasResolved && (!$fechaInicialIsValid || !$fechaTerminoIsValid)) {
    die("Invalid date format.");
}

$fechaInicialResolved = $fechaInicialIsValid ? date('Y-m-d', strtotime($fechaInicialResolved)) : '';
$fechaTerminoResolved = $fechaTerminoIsValid ? date('Y-m-d', strtotime($fechaTerminoResolved)) : '';

// Start building query
$query = "SELECT * FROM vista_tickets_resueltos";


//CREATE or replace VIEW vista_tickets_resueltos AS
//SELECT 
//   u.nombre, 
//  COUNT(t.id_estado_tk) AS tickets_resueltos
//FROM 
//   usuarios u
//LEFT JOIN 
//  ticket t ON u.rut_user = t.usuarios_rut_user 
//AND t.id_estado_tk = (SELECT id_estado_tk FROM estado_ticket WHERE nombre = 'Resuelto')
//GROUP BY 
//  u.nombre;





// If filtering by dates, add WHERE clause
if ($usarFechasResolved && $fechaInicialIsValid && $fechaTerminoIsValid) {
    $query .= " AND DATE(t.fecha_creacion) BETWEEN ? AND ?";
}


// Prepare statement
$stmt = $conexion->prepare($query);

// Bind parameters if necessary
if ($usarFechasResolved && $fechaInicialIsValid && $fechaTerminoIsValid) {
    $stmt->bind_param("ss", $fechaInicialResolved, $fechaTerminoResolved);
}

$stmt->execute();
$result = $stmt->get_result();

$labels = []; // For user names
$data = []; // For the number of resolved tickets

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['nombre'];
        $data[] = $row['tickets_resueltos'];
    }
}

// Create an associative array for the data
$chartData = [
    'labels' => $labels,
    'data' => $data
];

// Return the data in JSON format
header('Content-Type: application/json');
echo json_encode($chartData);
