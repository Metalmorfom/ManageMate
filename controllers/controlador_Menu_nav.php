<?php
// Incluye el archivo de conexión a la base de datos
include("../config/conexion_bd.php");

try {
    // Consulta SQL para obtener la lista de empresas
    $sql = "SELECT rut_empresa, nombre FROM empresa";

    // Ejecuta la consulta
    $resultado = $conexion->query($sql);

    // Verifica si la consulta se realizó con éxito
    if ($resultado) {
        // Inicializa una cadena vacía para almacenar las opciones del menú
        $opcionesMenu = "";

        // Agrega la opción predeterminada
        $opcionesMenu .= '<option value="Servicios Gestionados" selected>Servicios Gestionados</option>';

        // Itera a través de los resultados y construye las opciones del menú
        while ($fila = $resultado->fetch_assoc()) {
            $rutEmpresa = $fila['rut_empresa'];
            $nombreEmpresa = $fila['nombre'];
            $opcionesMenu .= '<option value="' . $rutEmpresa . '">' . $nombreEmpresa . '</option>';
        }

    } else {
        // Maneja el error si la consulta no se realizó correctamente
        echo "Error en la consulta: " . $conexion->error;
    }
} catch (Exception $e) {
    // Maneja la excepción si ocurre un error en la conexión o la consulta
    echo "Error en la consulta: " . $e->getMessage();
}
?>

