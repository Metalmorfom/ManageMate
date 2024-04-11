<?php
// buscar_usuario.php
include("../config/conexion_bd.php");

if (isset($_POST['texto'])) {
    $texto = $_POST['texto'];

    // Prepara la consulta para evitar inyecciones SQL
    $query = "SELECT rut_user, CONCAT(nombre, ' ', ap_paterno, ' ', ap_materno) as nombre_usuario FROM usuarios WHERE CONCAT(nombre, ' ', ap_paterno, ' ', ap_materno) LIKE ?";
    $textoBusqueda = '%' . $texto . '%';
    $stmt = $conexion->prepare($query);
    
    // Verifica si la preparación fue exitosa
    if($stmt) {
        $stmt->bind_param("s", $textoBusqueda);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $nombreUsuario = $row['nombre_usuario'];
            $rutUsuario = $row['rut_user'];
            // Agrega atributos data- al elemento <p>
            echo "<p data-nombre='{$nombreUsuario}' data-rut='{$rutUsuario}' onclick='seleccionarUsuario(\"{$nombreUsuario}\", \"{$rutUsuario}\")'>{$nombreUsuario}</p>";
        }
    } else {
        echo "Error al preparar la consulta.";
    }
}
?>



<?php
/*
// buscar_usuario.php
include("../config/conexion_bd.php");

$rut_usuario_activo = $_SESSION["usuario"]["rut_user"];

if (isset($_POST['texto'])) {
    $texto = $_POST['texto'];

    $query = "SELECT rut_user, CONCAT(nombre, ' ', ap_paterno, ' ', ap_materno) as nombre_usuario FROM usuarios WHERE CONCAT(nombre, ' ', ap_paterno, ' ', ap_materno) LIKE '%$texto%' AND rut_user != '$rut_usuario_activo'";
    $result = $conexion->query($query);

    while ($row = $result->fetch_assoc()) {
        $nombreUsuario = $row['nombre_usuario'];
        $rutUsuario = $row['rut_user'];
        echo "<p onclick='seleccionarUsuario(\"$nombreUsuario\", \"$rutUsuario\")'>$nombreUsuario</p>";
    }
}*/
?>
