<?php
include("../../config/conexion_bd.php");

// Get the 'rutEmpresa' from the AJAX request
$rutEmpresa = json_decode(file_get_contents('php://input'))->rutEmpresa;

$response = ['existe' => false]; // Default response

if (!empty($rutEmpresa)) {
    // Prepare the query
    $query = "SELECT * FROM empresa WHERE rut_empresa = ?";
    $stmt = $conexion->prepare($query);
    
    // Bind parameters and execute
    $stmt->bind_param("s", $rutEmpresa);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if any company exists with the given RUT
    if ($result->num_rows > 0) {
        $response['existe'] = true;
    }
    
    $stmt->close();
}

$conexion->close();

// Set header to respond with JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
