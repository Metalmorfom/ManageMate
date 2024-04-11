<?php
//buscar_empresa.php
include("../config/conexion_bd.php");

if (isset($_POST['texto'])) {
    $texto = $_POST['texto'];

    $query = "SELECT nombre, rut_empresa FROM empresa WHERE nombre LIKE '%$texto%'";
    $result = $conexion->query($query);

    while ($row = $result->fetch_assoc()) {
        $nombreEmpresa = $row['nombre'];
        $rutEmpresa = $row['rut_empresa'];
        // Dentro del bucle que procesa los resultados de la base de datos:
echo "<p data-nombre='{$nombreEmpresa}' data-rut='{$rutEmpresa}' onclick='seleccionarEmpresa(\"$nombreEmpresa\", \"$rutEmpresa\")'>$nombreEmpresa</p>";

    }
    // Cierra la conexión a la base de datos después de usarla
    $conexion->close();
}
?>
