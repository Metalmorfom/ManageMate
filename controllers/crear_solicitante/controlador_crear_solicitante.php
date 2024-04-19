<?php
include("../../config/conexion_bd.php");

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $campos_requeridos = ['nombre_empresa', 'rut_cliente', 'nombre', 's_nombre', 'apellidos', 'correo', 'telefono'];
    foreach ($campos_requeridos as $campo) {
        if (!isset($_POST[$campo])) {
            $response['message'] = "Falta un campo requerido: " . $campo;
            http_response_code(400); // Bad Request
            echo json_encode($response);
            exit;
        }
    }

    $nombre_empresa = $_POST["rut_empresa"];
    $rut_cliente = $_POST["rut_cliente"];
    $nombre = $_POST["nombre"];
    $s_nombre = $_POST["s_nombre"];
    $apellidos = $_POST["apellidos"];
    $correo = $_POST["correo"];
    $telefono = $_POST["telefono"];

    if ($conexion) {
        try {
            $sql = "INSERT INTO cliente (rut_cliente, nombre, s_nombre, apellidos, correo, rut_empresa, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = $conexion->prepare($sql)) {
                $stmt->bind_param("sssssii", $rut_cliente, $nombre, $s_nombre, $apellidos, $correo, $nombre_empresa, $telefono);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Cliente creado exitosamente.";
                } else {
                    $response['message'] = "Error al crear el cliente: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $response['message'] = "Error al preparar la consulta: " . $conexion->error;
            }
        } catch (mysqli_sql_exception $ex) {
            if ($ex->getCode() === 1062) { // Código de error para duplicación de entrada
                $response['message'] = "El número de teléfono ya existe.";
            } elseif ($ex->getCode() === 1064) { // Código de error para violación de clave primaria
                $response['message'] = "Ya existe un cliente con este número de rut.";
            } else {
                $response['message'] = "Error: " . $ex->getMessage();
            }
        } finally {
            $conexion->close();
        }
    } else {
        $response['message'] = "Error de conexión a la base de datos.";
    }


 
    echo json_encode($response);
}
?>
