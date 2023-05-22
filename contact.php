<?php
require 'vendor/autoload.php';
require_once 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = validarTexto($_POST['nombre']);
    $email = validarEmail($_POST['email']);
    $mensaje = validarTexto($_POST['mensaje']);

    if (!$nombre || !$email || !$mensaje) {
        http_response_code(400);
        echo json_encode(['message' => 'Datos de entrada inválidos.']);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

	    $mail->Username = $correo;
	    $mail->Password = $contrasena;


        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($email, $nombre);
        $mail->addAddress($correo); // Reemplaza con tu dirección de correo

        $mail->isHTML(false);
        $mail->Subject = 'Nuevo mensaje de contacto';
        $mail->Body = "Nombre: " . $nombre . "\n\n" . "Correo electrónico: " . $email . "\n\n" . "Mensaje: " . $mensaje;

        $mail->send();

        http_response_code(200);
        echo json_encode(['message' => 'Mensaje enviado correctamente.']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Error al enviar el mensaje.']);
    }
}

function validarTexto($texto) {
    $texto = trim($texto);
    $texto = stripslashes($texto);
    $texto = htmlspecialchars($texto);
    return $texto;
}

function validarEmail($email) {
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $email;
}
