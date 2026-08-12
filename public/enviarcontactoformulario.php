<?php
// Namespaces PHPMailer al inicio del archivo
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

// Evitar cache
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Variable para mostrar retroalimentación
$mensaje_exito = "";

// Procesar solo si es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recoger datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $mensaje_form = $_POST['mensaje'] ?? '';

    // Validar campos obligatorios
    if (empty($nombre) || empty($email) || empty($mensaje_form)) {
        echo "Por favor complete los campos obligatorios";
        exit;
    }

    
    // Guardar en base de datos
   
    $host = "localhost";
    $user = "root";      // Usuario root
    $pass = "";          // Contraseña root (vacía si no tienes)
    $db   = "contactos"; // Nombre de la base de datos

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO contactos (nombre, email, telefono, mensaje) VALUES (:nombre, :email, :telefono, :mensaje)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':telefono' => $telefono,
            ':mensaje' => $mensaje_form
        ]);
    } catch (PDOException $e) {
        echo "Error al guardar en la base de datos: " . $e->getMessage();
        exit;
    }

 
    // Enviar correo PHPMailer
    
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP(true);
        $mail->CharSet = 'UTF-8';
        $mail->Host = 'smtp.office365.com';  
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth   = true;
        $mail->setLanguage('es');
        $mail->Username   = 'correo5@g.com';
        $mail->Password   = 'contraseña54546ad';
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('CORREO@.com', 'Ana Yancy Aguilar');
        $mail->addAddress('lic.anayancyaguilar@gmail.com', 'Ana Yancy Aguilar');
        $mail->Subject = 'Nuevo mensaje de contacto';
        $mail->Body    = "Nombre: $nombre\nEmail: $email\nTeléfono: $telefono\nMensaje: $mensaje_form";

        $mail->send();
   
        // Redirigir a página de gracias
        header("Location: gracias.php");
    exit;
    } catch (Exception $e) {
        $mensaje_exito = "Error al enviar el mensaje: " . $mail->ErrorInfo;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Formulario de Contacto</h3>

    <?php if ($mensaje_exito): ?>
        <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
    <?php endif; ?>

    <!-- Incluir formulario desde archivo HTML -->
   <?php include __DIR__ . '/../src/utility/formulariocontacto.html'; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
