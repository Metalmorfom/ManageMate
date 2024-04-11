<?php
// Incluir la conexión a la base de datos (asegúrate de tener este archivo)
include("../config/conexion_bd.php");

if (isset($_POST['id_marca'])) {
    $id_marca = $_POST['id_marca'];

    // Consulta SQL para obtener los modelos basados en la marca seleccionada
    $query_modelos = "SELECT id_modelo, nombre FROM MODELO WHERE id_marca = $id_marca";
    $result_modelos = $conexion->query($query_modelos);

    if ($result_modelos) {
        // Generar opciones de modelos en formato HTML
        $options = "<option value=''>Seleccionar Modelo</option>";

        while ($row = $result_modelos->fetch_assoc()) {
            $id_modelo = $row['id_modelo'];
            $nombre_modelo = $row['nombre'];
            $options .= "<option value='$id_modelo'>$nombre_modelo</option>";
        }

        echo $options; // Devolver las opciones de modelos como respuesta
    } else {
        // Manejo de errores si la consulta falla
        echo "Error en la consulta: " . $conexion->error;
    }
} else {
    // Manejo de errores si no se proporciona el ID de la marca
    echo "ID de marca no proporcionado.";
}
?>
