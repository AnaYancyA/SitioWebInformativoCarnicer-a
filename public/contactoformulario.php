<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar entradas
    $nombre   = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $email    = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_NUMBER_INT);
    $mensaje  = filter_var($_POST['mensaje'], FILTER_SANITIZE_STRING);

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host       = 'smtp.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '115670605@globalcertacademy.com';
        $mail->Password   = 'H/991268854546ad';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('115670605@globalcertacademy.com', 'Ana Yancy Aguilar');
        $mail->addAddress('lic.anayancyaguilar@gmail.com', 'Ana Yancy Aguilar');

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Nuevo contacto desde el formulario';
        $mail->Body    = "
            <b>Nombre:</b> $nombre<br>
            <b>Email:</b> $email<br>
            <b>Teléfono:</b> $telefono<br>
            <b>Mensaje:</b> $mensaje
        ";
        $mail->AltBody = "Nombre: $nombre\nEmail: $email\nTeléfono: $telefono\nMensaje: $mensaje";

        $mail->send();

        // Redirigir correctamente al formulario
        echo "<script>
                alert('Mensaje enviado correctamente');
                window.history.back();
              </script>";

    } catch (Exception $e) {
        echo "<script>
                alert('Error al enviar mensaje: {$mail->ErrorInfo}');
                window.history.back();
              </script>";
    }
}
?>
