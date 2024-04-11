<?php
// buscar_cliente.php
include("../config/conexion_bd.php");

if (isset($_POST['rut_empresa']) && isset($_POST['texto'])) {
    $rutEmpresa = $_POST['rut_empresa'];
    $texto = $_POST['texto'];

    // Consulta SQL para buscar clientes relacionados con la empresa y que coincidan con el texto de búsqueda
    $query = "SELECT rut_cliente, CONCAT(nombre, ' ', COALESCE(s_nombre, ''), ' ', apellidos) as nombre FROM cliente WHERE rut_empresa = ? AND (nombre LIKE '%$texto%' OR apellidos LIKE '%$texto%')";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $rutEmpresa);
    $stmt->execute();
    $result = $stmt->get_result();

    // Generar la lista de opciones para el campo de clientes
    $options = "";
    while ($row = $result->fetch_assoc()) {
        $rutCliente = $row['rut_cliente'];
        $nombreCliente = $row['nombre'];
        $options .= "<p onclick='seleccionarCliente(\"$nombreCliente\", \"$rutCliente\")'>$nombreCliente</p>";
    }

    echo $options;
}
?>
