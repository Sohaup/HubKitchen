<?php

namespace PostApi\modules\CS\helpers\adapters\mailer;

use Exception;
use Override;
use PHPMailer\PHPMailer\PHPMailer;
use PostApi\shared\config\Env;

class Mailer implements Mail
{
    private PHPMailer $mail;
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
    }
    #[Override]
    public function authorisaze(string $to, string $customerName)
    {
        try {
            Env::configureEnv();            
            $this->mail->isSMTP();
            $this->mail->Host = $_ENV['MAILER_HOST'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $_ENV['MAILER_USER_NAME'];
            $this->mail->Password = $_ENV['MAILER_PASSWORD'];
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = $_ENV['MAILER_PORT_ONE'];
            $this->mail->setFrom($_ENV['MAILER_USER_NAME'], "PostApi ERP Project");
            $this->mail->addAddress($to, $customerName);
        } catch (Exception $error) {
            throw new Exception($error->getMessage());
        }
    }

    #[Override]
    public function buildMail(string $subject, string $body, string $altBody)
    {
        try {
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->AltBody = $altBody;
        } catch (Exception $error) {
            throw new Exception($error->getMessage());
        }
    }

    #[Override]
    public function send()
    {
        try {
            $this->mail->send();
        } catch (Exception $error) {
            throw new Exception($error->getMessage());
        }
    }
}
