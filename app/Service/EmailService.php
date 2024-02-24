<?php

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Han\Utils\Service;
use Hyperf\Contract\ConfigInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService extends Service
{

    /**
     * @param $toEmail
     * @param $code
     * @return void
     */
    public function send($toEmail, $code)
    {
        $mail = new PHPMailer(true);

        $emailUsername = $this->container->get(ConfigInterface::class)->get('email.username');
        $emailPassword = $this->container->get(ConfigInterface::class)->get('email.password');
        $emailFrom = $this->container->get(ConfigInterface::class)->get('email.from');
        $emailName = $this->container->get(ConfigInterface::class)->get('email.name');

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.163.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $emailUsername;                     //SMTP username
            $mail->Password   = $emailPassword;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($emailFrom, $emailName);
            $mail->addAddress($toEmail);     //Add a recipient
//            $mail->addAddress('ellen@example.com');               //Name is optional
//            $mail->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');

            //Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = '您的 Black List 注册验证码是:' . $code;
            $mail->Body    = "您的 Black List 注册验证码是: <b>{$code}</b>";
//            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            # TODO::Ranger 这里要后续记录日志 错误日志不在接口输出
            throw new BusinessException(ErrorCode::AUTH_EMAIL_SEND_ERROR, "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}