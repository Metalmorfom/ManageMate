<?php
include("../config/conexion_bd.php");

if (isset($_POST['rut_empresa'])) {
    $rutEmpresa = $_POST['rut_empresa'];
    echo $rutEmpresa;

    
    $stmt = $conexion->prepare("SELECT rut_cliente, CONCAT(nombre, ' ', COALESCE(s_nombre, ''), ' ', apellidos) as nombre FROM cliente WHERE rut_empresa = ? ");
    $stmt->bind_param("s", $rutEmpresa);
    $stmt->execute();
    $result = $stmt->get_result();

    // Generar la lista de opciones para el campo de clientes
    $options = "<option value=''>Seleccione</option>";
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row['rut_cliente'] . "'>" . $row['nombre'] . "</option>";
    }

    echo $options;
} else {
    echo "<option value=''>Seleccione</option>";
}
?>
