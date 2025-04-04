<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../userSrc/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../userSrc/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../../userSrc/PHPMailer/src/Exception.php';

function enviarCorreoRecuperacion($destinatario, $nombre, $enlace) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'jorgemoruagonzalez@gmail.com';
        $mail->Password   = 'dqzj pumm kyei sisu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('jorgemoruagonzalez@gmail.com', 'Soporte SAM Design');
        $mail->addAddress($destinatario, $nombre);

        $mail->isHTML(true);
        $mail->Subject = 'Recuperacion de contraseña';
        $mail->Body    = "
            Hola <strong>$nombre</strong>,<br><br>
            Para cambiar tu contraseña, haz clic en el siguiente enlace:<br>
            <a href='$enlace'>$enlace</a><br><br>
            Este enlace expirará en 1 hora.
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
