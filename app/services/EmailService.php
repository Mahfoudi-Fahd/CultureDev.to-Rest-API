<?php
namespace App\Services;
use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    protected $app_name;
    protected $host;
    protected $port;
    protected $username;
    protected $password;
    function __construct()
    {
        $this->app_name = config('app.name');
        $this->host = config('app.mail_host');
        $this->port = config('app.mail_port');
        $this->username = config('app.mail_username');
        $this->password = config('app.mail_password');
    }
    public function resetPassword($subject,$emailUser,$nameUser,$isHtml,$activation_token)
    {
        $mail= new PHPMailer;
        $mail->isSMTP();                                            //Send using SMTP
        $mail->SMTPDebug = 2;                      //Enable verbose debug output
        $mail->Host       = $this->host;                     //Set the SMTP server to send through
        $mail->Port       = $this->port;       
        $mail->Username   = $this->username;                     //SMTP username
        $mail->Password   = $this->password;                               //SMTP password
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Subject    = $subject;
        $mail->setFrom($emailUser,$emailUser);
        $mail->addReplyTo('no_reply@gmail.com',$this->app_name);
        $mail->addAddress($emailUser,$nameUser);
        $mail->isHTML($isHtml);
        $mail->Body=$this->viewResetPassword($nameUser,$activation_token);
        $mail->send();
    }
    public function viewResetPassword($name,$activation_token)
    {
        return view('mail.reset_password')
        ->with([
         'name' => $name,
         'activation_token' => $activation_token 
        ]);
    }
}   
