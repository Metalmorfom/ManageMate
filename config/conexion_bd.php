<?php
// conexion_bd.php
date_default_timezone_set('America/Santiago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use Dotenv\Dotenv;
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$usuario_activo = $_SESSION["usuario"]["User"];
$PWD_activo = $_SESSION["usuario"]["clave_db"];
$rut_usuario_activo = $_SESSION["usuario"]["rut_user"];

// AWS BD Credentials
$db_host = $_ENV['DB_HOST'];
$db_user = $usuario_activo;
$db_password = $PWD_activo;
$db_name = $_ENV['DB_NAME'];
$db_port = $_ENV['DB_PORT'];


//google DB credencialts
//$db_host = "34.151.229.8";
//$db_user = "root";
//$db_password = "Darketon12";
//$db_name = "Acis_ticket";
//$db_port = "3

// Configurar mysqli para lanzar excepciones
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Intentar crear una conexión a la base de datos
    $conexion = new mysqli($db_host, $db_user, $db_password, $db_name, $db_port);

    // Establecer el conjunto de caracteres a UTF-8
    $conexion->set_charset("utf8");

    // Código para trabajar con la base de datos aquí
    // ...

} catch (mysqli_sql_exception $e) {
    // Manejo del error
    echo "Error al conectar a la base de datos: " . $e->getMessage();
    // Decidir cómo manejar el error, por ejemplo, terminando la ejecución del script
    exit; // Finaliza la ejecución del script
} finally {
    // Este bloque es opcional y se ejecuta siempre, independientemente de si se lanzó una excepción o no.
    // Es útil para limpiar recursos, cerrar conexiones, etc.
    // Nota: Aquí no cerramos la conexión ya que se usará en el script más adelante.
}
