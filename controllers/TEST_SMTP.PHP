<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();

$mail = new PHPMailer();
$smtpHost = $_ENV['SMTP_HOST'];
$smtpUsername = $_ENV['SMTP_USERNAME'];
$smtpPassword = $_ENV['SMTP_PASSWORD'];
$smtpSecure = $_ENV['SMTP_SECURE'];
$smtpPort = $_ENV['SMTP_PORT'];

// Configura el servidor SMTP de Brevo
$mail->isSMTP();
$mail->Host = $smtpHost; // Servidor SMTP de Brevo
$mail->SMTPAuth = true;
$mail->Username = $smtpUsername; // Tu dirección de correo
$mail->Password = $smtpPassword; // Contraseña SMTP de Brevo
$mail->SMTPSecure = $smtpSecure; // Usa 'tls' como método de seguridad
$mail->Port = $smtpPort; // Puerto SMTP de Brevo


// Prueba de conexión al servidor SMTP
try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Activa la depuración del servidor SMTP
    $mail->SMTPAutoTLS = false; // Desactiva la auto-negociación de TLS
    $mail->SMTPSecure = false; // Desactiva el uso de seguridad SMTP
    $mail->Timeout = 5; // Establece un tiempo de espera en segundos

    $connected = $mail->smtpConnect();
    if (!$connected) {
        echo 'No se pudo conectar al servidor SMTP. Verifica la configuración.';
        exit();
    } else {
        echo '<div id="smtp-logs"></div>';
        echo "<br>";
        echo 'Conexión al servidor SMTP exitosa.';
        echo "<br>";
        echo 'Registro de comunicación SMTP:';
        echo "<br>";
        echo '<pre>' . $mail->SMTPDebug . '</pre>'; // Muestra la traza de comunicación SMTP
        echo '<script>document.getElementById("smtp-logs").innerHTML = ""; setTimeout(function() { window.close(); }, 9000);</script>'; // Limpia el contenido anterior y cierra la ventana después de 3 segundos
        $mail->smtpClose();
    }
} catch (Exception $e) {
    echo 'Error al intentar conectar al servidor SMTP: ' . $e->getMessage();
    exit();
}
