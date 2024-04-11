<?php
// conex_bd.php
date_default_timezone_set('America/Santiago');

use Dotenv\Dotenv;
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// AWS BD Credentials
$db_host = $_ENV['DB_HOST'];
$db_user = $_ENV['USER_GUARD'];
$db_password = $_ENV['USER_GUARD_PWD'];
$db_name = $_ENV['DB_NAME'];
$db_port = $_ENV['DB_PORT'];

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
    //echo "Error al conectar a la base de datos: " . $e->getMessage();

    echo "Error al conectar a la base de datos: " ;
    // Decidir cómo manejar el error, por ejemplo, terminando la ejecución del script
    exit; // Finaliza la ejecución del script
} finally {
    // Este bloque es opcional y se ejecuta siempre, independientemente de si se lanzó una excepción o no.
    // Es útil para limpiar recursos, cerrar conexiones, etc.
    // Nota: Aquí no cerramos la conexión ya que se usará en el script más adelante.
}
