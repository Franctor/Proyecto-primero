<?php
namespace services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\Plates\Engine;

class MailService
{
    private $templates;

    public function __construct(Engine $templates)
    {
        $this->templates = $templates;
    }

    public function enviarCorreo($destinatario, $asunto, $plantilla, $datos = [])
    {
        $html = $this->templates->render($plantilla, $datos);

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = getenv('MAIL_HOST') ?: 'mailhog';
            $mail->Port = getenv('MAIL_PORT') ?: 1025;
            $mail->SMTPAuth = false;

            $mail->setFrom('no-reply@miapp.com', 'Mi App');
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $html;

            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
