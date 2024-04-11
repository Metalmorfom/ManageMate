<?php

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();

//include("../config/conexion_bd.php"); // Incluye el archivo de conexión a la base de datos.
include("config/conex_db.php"); // Incluye el archivo de conexión a la base de datos.
// Obtiene la hora actual
$hora_actual = date('Y-m-d H:i:s');
mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR); // Configura MySQLi para lanzar excepciones

session_start();

if (isset($_POST["bntingresar"])) {
    if (empty($_POST["usuario"]) || empty($_POST["password"])) {
        // Comprueba si los campos de usuario o contraseña están vacíos y muestra un mensaje de error si es así.
        echo '<div class="alert alert-danger">Rellena todos los campos.</div>';
    } else {
        $usuario = $_POST["usuario"];
        $clave = $_POST["password"];

        // Prepara una consulta SQL utilizando sentencias preparadas para evitar inyecciones SQL.
        $stmt = $conexion->prepare("SELECT * FROM View_Users WHERE correo = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $nombreUsuario = $row['nombre'];
            $ap_paterno = $row['ap_paterno'];
            $ap_materno = $row['ap_materno'];
            $rut_user = $row['rut_user'];
            $user_db = $row['User'];
            $pwd_db = $row['Authentication_String'];

            if ($row['id_estado'] === 1 || $row['id_estado'] === 7) {
                // Hashea la contraseña antes de almacenarla en la base de datos
                date_default_timezone_set('America/Santiago');

                // AWS BD Credentials 
                $db_host = $_ENV['DB_HOST'];
                $db_user = $user_db;
                $db_password = $clave;
                $db_name = $_ENV['DB_NAME'];
                $db_port = $_ENV['DB_PORT'];

                try {
                    // Crear una conexión a la base de datos
                    $conexion = new mysqli($db_host, $db_user, $db_password, $db_name, $db_port);

                    // Verificar la conexión
                    if ($conexion->connect_error) {
                      
                        echo '<div class="alert alert-danger">la pwd no existe.</div>';
                    } else {
                        // Verifica si la fecha de expiración es mayor a la fecha actual
                        $fecha_expiracion = $row['fecha_expiracion'];
                        if (strtotime($fecha_expiracion) > strtotime($hora_actual)) {
                            $_SESSION["usuario"] = array(
                                "correo" => $usuario,
                                "nombre" => $nombreUsuario,
                                "ap_paterno" => $ap_paterno,
                                "ap_materno" => $ap_materno,
                                "rut_user" => $rut_user,
                                "User" => $user_db,
                                "clave_db" => $clave,
                            );

                            // Imprimir la URL guardada
                            if (isset($_SESSION['redirect_url'])) {
                                echo "URL guardada: " . $_SESSION['redirect_url'] . "<br>";
                            }

                            // Redirección después del inicio de sesión
                            if (isset($_SESSION['redirect_url'])) {
                                $redirect_url = $_SESSION['redirect_url'];
                                unset($_SESSION['redirect_url']);
                                header("Location: $redirect_url");
                            } else {
                                // Redirige a la página de inicio por defecto
                                header("Location: views/inicio.php");
                            }
                            exit();
                        } else {
                            echo '<div class="alert alert-danger">Tu cuenta ha expirado. Debes Contactar con un administrador.</div>';
                        }
                    }
                } catch (mysqli_sql_exception $e) {
                   echo '<div class="alert alert-danger">Comprueba tus Credenciales </div>';
                   // echo '<div class="alert alert-danger">Error de conexión: ' . $e->getMessage() . '</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Tu cuenta no está activa. Debes verificar tu correo para activarla.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Comprueba tus Credenciales. </div>';
        }
    }
}
?>
