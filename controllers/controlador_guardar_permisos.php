<?php
session_start();
// controlador_guardar_permisos.php

include("../config/conexion_bd.php");

$data = json_decode(file_get_contents("php://input"));
$response = ["success" => false];



try {
    if (!empty($data->rutUsuario)) {
        $rutUsuario = $data->rutUsuario;
        $idsPermisos = $data->idsPermisos;
        $idsPermisosEliminar = $data->idsPermisosEliminar; // Captura los permisos a eliminar
        $datosFormulario = $data->datosFormulario; // Obtener los datos del formulario

        $conexion->begin_transaction(); // Comenzar transacción
        $errorOcurrido = false;

        // Procesar la eliminación de permisos
        foreach ($idsPermisosEliminar as $idPermiso) {
            $sqlDelete = "DELETE FROM permisos_usuario WHERE rut_user = ? AND id_permiso_indi = ?";
            if ($stmtDelete = $conexion->prepare($sqlDelete)) {
                $stmtDelete->bind_param("si", $rutUsuario, $idPermiso);
                if ($stmtDelete->execute()) {
                    // Solo llamar a revocarPermisoDB si la eliminación fue exitosa
                  //  revocarPermisoDB($conexion, $rutUsuario, $idPermiso);
                } else {
                    // Manejar error en la ejecución de DELETE
                    $errorOcurrido = true;
                    break; // Salir del bucle en caso de error
                }
                $stmtDelete->close();
            } else {
                // Error en la preparación del DELETE
                $errorOcurrido = true;
                break; // Salir del bucle en caso de error
            }
        }


        if (!$errorOcurrido) {
            // Procesar la actualización de los datos del formulario
            $sqlUpdate = "UPDATE usuarios SET nombre = ?, s_nombre = ?, ap_paterno = ?, ap_materno = ?, correo = ?, fecha_expiracion = ?, telefono = ?, id_codigo = ? WHERE rut_user = ?";
            if ($stmtUpdate = $conexion->prepare($sqlUpdate)) {
                $stmtUpdate->bind_param("ssssssiss", $datosFormulario->nombre, $datosFormulario->s_nombre, $datosFormulario->ap_paterno, $datosFormulario->ap_materno, $datosFormulario->correo, $datosFormulario->fecha_expiracion, $datosFormulario->telefono, $datosFormulario->id_codigo, $rutUsuario);
                if (!$stmtUpdate->execute()) {
                    $errorOcurrido = true;
                }
                $stmtUpdate->close();
            } else {
                // Error en la preparación del UPDATE
                $errorOcurrido = true;
            }
        }

        if (!$errorOcurrido) {
            // Procesar la inserción de permisos
            foreach ($idsPermisos as $idPermiso) {
                // Verificar si el permiso ya existe para el usuario
                $sqlCheck = "SELECT COUNT(*) FROM permisos_usuario WHERE rut_user = ? AND id_permiso_indi = ?";
                if ($stmtCheck = $conexion->prepare($sqlCheck)) {
                    $stmtCheck->bind_param("si", $rutUsuario, $idPermiso);
                    $stmtCheck->execute();
                    $stmtCheck->bind_result($count);
                    $stmtCheck->fetch();
                    $stmtCheck->close();

                    // Si no existe, insertar el permiso
                    if ($count == 0) {
                        $sqlInsert = "INSERT INTO permisos_usuario (rut_user, id_permiso_indi) VALUES (?, ?)";
                        if ($stmtInsert = $conexion->prepare($sqlInsert)) {
                            $stmtInsert->bind_param("si", $rutUsuario, $idPermiso);
                            if (!$stmtInsert->execute()) {
                                $errorOcurrido = true;
                                break; // Salir del bucle en caso de error
                            }
                            $stmtInsert->close();

                            // Lógica para otorgar permisos de DB aquí
                            //otorgarPermisoDB($conexion, $rutUsuario, $idPermiso);
                        } else {
                            // Error en la preparación del INSERT
                            $errorOcurrido = true;
                            break; // Salir del bucle en caso de error
                        }
                    }
                } else {
                    // Error en la preparación del SELECT
                    $errorOcurrido = true;
                    break; // Salir del bucle en caso de error
                }
            }
        }


        if (!$errorOcurrido) {
            // Si todo salió bien, hacer commit de la transacción
            $conexion->commit();
            $response["success"] = true;
        } else {
            $conexion->rollback();
            $response["error"] = "Ocurrió un error durante el procesamiento.";
        }
    } else {
        $response["error"] = "Datos insuficientes";
    }
} catch (Exception $e) {
    // Si ocurre alguna excepción, hacer rollback de la transacción y establecer un mensaje de error
    $conexion->rollback();
    $response["error"] = "Ocurrió un error durante el procesamiento: " . $e->getMessage();
}




function obtenerNombreUsuarioDB($conexion, $rutUsuario)
{
    $sql = "SELECT `User` FROM View_Users WHERE rut_user = '$rutUsuario'";
    $resultado = $conexion->query($sql);

    if ($resultado) {
        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $usuarioDB = $fila['User'];
            $resultado->free();
            return $usuarioDB;
        } else {
            echo json_encode("No se encontró ningún usuario con el rut $rutUsuario.");
        }
    } else {
        echo json_encode("Error ejecutando consulta: " . $conexion->error);
    }

    return null; // Si no se encuentra el usuario o hay un error
}


// Función para otorgar permisos de base de datos
function otorgarPermisoDB($conexion, $rutUsuario, $idPermiso, $commit = true)
{
    // Obtiene el nombre de usuario de la DB asociado al rutUsuario
    $usuarioDB = obtenerNombreUsuarioDB($conexion, $rutUsuario);
    if ($usuarioDB !== null) { // Verifica si se encontró el usuario
        switch ($idPermiso) {
            case 1:
                $sqlGrant = "GRANT SELECT ON acis_ticket.ticket TO '$usuarioDB'@'%'";
                break;
            case 2:
                $sqlGrant = "GRANT INSERT,UPDATE ON acis_ticket.cliente TO '$usuarioDB'@'%';";
                $sqlGrant .= "GRANT SELECT ON acis_ticket.empresa TO '$usuarioDB'@'%'; ";
                break;
            // Agrega más casos según tus necesidades
            default:
                echo "IdPermiso no válido.";
                return;
        }
        // Ejecutar las instrucciones SQL de GRANT
        if ($conexion->multi_query($sqlGrant)) {
            do {
                // Necesario para consumir cualquier resultado, aunque no esperamos ninguno aquí.
            } while ($conexion->more_results() && $conexion->next_result());

            // Después de procesar todos los resultados de las consultas anteriores
            if ($commit) {
                // Ahora es seguro ejecutar FLUSH PRIVILEGES u otros comandos
                if (!$conexion->query("FLUSH PRIVILEGES;")) {
                    echo "Error ejecutando FLUSH PRIVILEGES: " . $conexion->error;
                }
            }
        } else {
            echo "Error otorgando permiso: " . $conexion->error;
        }
    } else {
        echo "No se encontró el usuario.";
    }
}



function revocarPermisoDB($conexion, $rutUsuario, $idPermiso, $commit = true)
{
    $usuarioDB = obtenerNombreUsuarioDB($conexion, $rutUsuario);
    if ($usuarioDB !== null) { // Verifica si se encontró el usuario
        switch ($idPermiso) {
            case 1:
                $sqlRevoke = "REVOKE SELECT ON acis_ticket.ticket FROM '$usuarioDB'@'%';";
                break;
            case 2:
                $sqlRevoke = "REVOKE INSERT, UPDATE ON acis_ticket.cliente FROM '$usuarioDB'@'%'; ";
                $sqlRevoke .= "REVOKE SELECT ON acis_ticket.empresa FROM '$usuarioDB'@'%';";
                break;
            // Agrega más casos según tus necesidades
            default:
                echo "IdPermiso no válido.";
                return;
        }
        // Ejecutar las instrucciones SQL de REVOKE
        if ($conexion->multi_query($sqlRevoke)) {
            do {
                // Necesario para consumir cualquier resultado, aunque no esperamos ninguno aquí.
            } while ($conexion->more_results() && $conexion->next_result());

            // Después de procesar todos los resultados de las consultas anteriores
            if ($commit) {
                // Ahora es seguro ejecutar FLUSH PRIVILEGES u otros comandos
                if (!$conexion->query("FLUSH PRIVILEGES;")) {
                    echo "Error ejecutando FLUSH PRIVILEGES: " . $conexion->error;
                } else {
                    // Confirmación de éxito si es necesario
                    echo "Permisos revocados y privilegios actualizados correctamente.";
                }
            }
        } else {
            echo "Error revocando permiso: " . $conexion->error;
        }
    } else {
        echo "No se encontró el usuario.";
    }
}





echo json_encode($response);
