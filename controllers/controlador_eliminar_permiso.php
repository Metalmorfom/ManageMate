<?php
// controlador_eliminar_permiso.php
session_start();
include("../config/conexion_bd.php");

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->rutUsuario) && !empty($data->idPermiso)) {
    $rutUsuario = $data->rutUsuario;
    $idPermiso = $data->idPermiso;

    $sql = "DELETE FROM permisos_usuario WHERE rut_user = ? AND id_permiso_indi = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("si", $rutUsuario, $idPermiso);
        if ($stmt->execute()) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("error" => "Error al ejecutar la consulta"));
        }
        $stmt->close();
    } else {
        echo json_encode(array("error" => "Error en la preparación de la consulta SQL"));
    }
    $conexion->close();
} else {
    echo json_encode(array("error" => "Datos insuficientes"));
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo json_encode($data);

?>
