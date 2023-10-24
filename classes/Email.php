<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

#[\AllowDynamicProperties]

class Email {

    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    
    public function enviarConfirmacion() {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '16323b864a8056';
        $mail->Password = '61c257944c7da2';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu cuenta';
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p>Hola <stong> " . $this->nombre . "</strong> Has creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['APP_URL'] ."/confirmar?token=" . $this->token . "'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si no solicitaste cuenta en UpTask, ignora este mensaje</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //Enviar
        $mail->send();

    }

    public function enviarInstrucciones() {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '16323b864a8056';
        $mail->Password = '61c257944c7da2';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Reestablece tu contraseña';
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p>Hola <stong> " . $this->nombre . "</strong> Parece que olvidaste tu contraseña, sigue el siguiente enlace para recuperarla</p>";
        $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['APP_URL'] ."/reestablecer?token=" . $this->token . "'>Reestablecer contraseña</a></p>";
        $contenido .= "<p>Si no solicitaste un cambio en UpTask, ignora este mensaje</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //Enviar
        $mail->send();

    }
}
