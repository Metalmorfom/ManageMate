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
$mail->Host = $smtpHost;// Servidor SMTP de Brevo
$mail->SMTPAuth = true;
$mail->Username = $smtpUsername;// dirección de correo
$mail->Password = $smtpPassword;// Contraseña SMTP de Brevo
$mail->SMTPSecure = $smtpSecure; // Usa 'tls' como método de seguridad
$mail->Port = $smtpPort;// Puerto SMTP de Brevo


// Configura el correo


$mail->addEmbeddedImage($GM_addEmbeddedImagen_ruta, $GM_addEmbeddedImagen_nombre);
$mail->addEmbeddedImage($GM_addEmbeddedImagen_ruta2, $GM_addEmbeddedImagen_nombre2);


$GM_addAdress_nombre = ucwords($GM_addAdress_nombre); // formato initcap
$GM_addAdress_ap_paterno = ucwords($GM_addAdress_ap_paterno); // formato initcap

$mail->setFrom('no-reply@ManageMate.cl', 'Soporte ManageMate');
$mail->addAddress($GM_addAdress_Correo, $GM_addAdress_nombre.' '. $GM_addAdress_ap_paterno); // Cambia esto al destinatario real
$mail->isHTML(true);
$mail->Subject = $GM_Subject;

    
$mail->CharSet = 'UTF-8';

$mail->Body = $GM_Body;


// Envía el correo
if ($mail->send()) {
   //echo 'El correo se ha enviado correctamente al correo: .'.$GM_addAdress_Correo;
} else {
    echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
}
