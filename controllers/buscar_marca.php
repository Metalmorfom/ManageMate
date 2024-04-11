<?php
// Incluir la conexión a la base de datos (asegúrate de tener este archivo)
include("../config/conexion_bd.php");

if (isset($_POST['texto'])) {
    $textoBusqueda = $_POST['texto'];

    // Realiza una consulta SQL para buscar marcas que coincidan con el texto
    $query = "SELECT id_marca, nombre FROM MARCA WHERE nombre LIKE '%$textoBusqueda%'";
    $result = $conexion->query($query);

    if ($result) {
        // Crear una lista de opciones de marca
        echo '<ul>';
        while ($row = $result->fetch_assoc()) {
            $idMarca = $row['id_marca'];
            $nombreMarca = $row['nombre'];
            echo '<li onclick="seleccionarMarca(\'' . $nombreMarca . '\', ' . $idMarca . ')">' . $nombreMarca . '</li>';
        }
        echo '</ul>';
    } else {
        echo 'Error en la consulta: ' . $conexion->error;
    }
} else {
    echo 'No se proporcionó un término de búsqueda.';
}
?>
