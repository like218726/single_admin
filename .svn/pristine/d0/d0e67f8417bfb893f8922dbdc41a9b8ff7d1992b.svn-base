<?php
/**
 * Created by PhpStorm.
 * User: wzj
 * Date: 2018/12/4
 * Time: 9:51
 */

namespace app\common;


class Email
{
    private $email_from_name = '太华音乐';                //发件人
    private $email_smtp       = 'smtp.exmail.qq.com';   // smtp
    private $email_username   = 'lijunhua@jitusoft.cn';   // 账号(腾讯企业邮箱)
    private $email_pasword     = 'Ljh159357';
    private $email_smtp_secure = 'ssl';
    private $email_port         = '465';

    /**
     * 导出excel
     * @param  $address	发送地址
     * @param string $subject 邮件标题
     * @param string $content 邮件内容
     * @param string $attachmentPath 附件地址
     * @param string $attachmentFileName 附件文件名
     */
    public function send_email($address,$subject,$content,$attachmentPath='',$attachmentFileName='')
    {
        $email_smtp         = $this -> email_smtp;
        $email_username     = $this -> email_username;
        $email_password     = $this -> email_pasword;
        $email_from_name    = $this -> email_from_name;
        $email_smtp_secure  = $this -> email_smtp_secure;
        $email_port         = $this -> email_port;
        require_once VENDOR_PATH.'/phpmailer/phpmailer.php';
        require_once VENDOR_PATH.'/phpmailer/smtp.php';
        $phpmailer = new \Phpmailer();
        // 设置PHPMailer使用SMTP服务器发送Email
        $phpmailer->IsSMTP();
        // 设置设置smtp_secure
        $phpmailer->SMTPSecure = $email_smtp_secure;
        // 设置port
        $phpmailer->Port = $email_port;
        // 设置为html格式
        $phpmailer->IsHTML(true);
        // 设置邮件的字符编码'
        $phpmailer->CharSet = 'UTF-8';
        // 设置SMTP服务器。
        $phpmailer->Host = $email_smtp;
        // 设置为"需要验证"
        $phpmailer->SMTPAuth = true;
        // 设置用户名
        $phpmailer->Username = $email_username;
        // 设置密码
        $phpmailer->Password = $email_password;
        // 设置邮件头的From字段。
        $phpmailer->From = $email_username;
        // 设置发件人名字
        $phpmailer->FromName = $email_from_name;
        // 添加收件人地址，可以多次使用来添加多个收件人
        if(is_array($address)){
            foreach($address as $addressv){
                $phpmailer->AddAddress($addressv);
            }
        } else {
            $phpmailer->AddAddress($address);
        }
        // 设置邮件标题
        $phpmailer->Subject = $subject;
        // 设置邮件正文
        $phpmailer->Body = $content;
        // 判断附件地址是否为空
        if($attachmentPath !== ''){
            // 设置邮件附件
            $phpmailer->addAttachment($attachmentPath,$attachmentFileName);
        }
        // 发送邮件。
        if(!$phpmailer->Send()){
            $phpmailererror = $phpmailer->ErrorInfo;

            return ["code"=>0,"message"=>$phpmailererror];
        } else {
            return ["code"=>1];
        }
    }
}