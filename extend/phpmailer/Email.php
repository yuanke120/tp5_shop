<?php
namespace phpmailer;


use think\Exception;

class Email
{
    /**
     * @param $to
     * @param $title
     * @param $content
     * @return bool
     */
public static function send($to,$title,$content)
{
    date_default_timezone_set('PRC');//set time
    require 'Phpmailer.php';
    require 'Smtp.php';
    $mail = new PHPMailer;
    if(empty($to)){
        return false;
    }
    try {
        //服务器配置
        $mail->CharSet ="UTF-8";                     //设定邮件编码
        $mail->SMTPDebug = 0;                        // 调试模式输出
        $mail->isSMTP();                             // 使用SMTP
        $mail->Host = config('email.host');               // SMTP服务器
        $mail->SMTPAuth = true;                      // 允许 SMTP 认证
        $mail->Username = config('email.username');               // SMTP 用户名  即邮箱的用户名
        $mail->Password = config('email.password');             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
        $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
        $mail->Port = config('email.port');                            // 服务器端口 25 或者465 具体要看邮箱服务器支持
        $mail->setFrom(config('email.username'), 'Yuan');  //发件人
        $mail->addAddress($to, 'honk'); // 收件人
        //$mail->addAddress('ellen@example.com');  // 可添加多个收件人
        $mail->addReplyTo(config('email.username'), 'Yuan'); //回复的时候回复给哪个邮箱 建议和发件人一致
        //Content
        $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
//        $mail->Subject = $title . time();
        $mail->Subject = $title;
       // $mail->Body    = $content . '<br/>'.date('Y-m-d H:i:s');
        $mail->Body  = $content ;
//        $mail->msgHTML($content);
        $mail->AltBody = '如果邮件客户端不支持HTML则显示此内容';

        $mail->send();
       // echo '邮件发送成功';
        if(!$mail->send()){
            return false;
        }else{
            return true;
        }
    } catch (phpmailerException $e) {
        return false;
        //echo '邮件发送失败: ', $mail->ErrorInfo;
    }
}
}