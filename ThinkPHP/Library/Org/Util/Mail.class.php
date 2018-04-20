<?php
/*
mail类属性:
mail(string $server,string [ $user,string $pass, boole $Info ])
server: SMTP服务器地址,默认为25端口,如果你的SMTP服务端口不是25,请在主机地址后加上冒号+端口,例如: 127.0.0.1:26
user: SMTP服务帐号 ( 可选, 如果服务器不需要验证可以不填 )
pass: SMTP服务密码 ( 可选, 如果服务器不需要验证可以不填 )
info: 是否显示调试信息 (可选, 默认不显示调试信息 )

send(邮件发送)参数:
boole send(string $from,string $to,string $title,string $content,[ string $attachment ])
from: 发件人
to: 收件人 ( 多个收件人用分号隔开,例如: i@darkend.net; m@darkend.net )
title: 标题
content: 内容 (支持HTML)
attachment: 附件 (可选, 包含路径和文件名,多个附件用分号分隔开,例如: file1.rar; file2.zip )

例子:
$smtp = new mail("smtp.darkend.net",'username','password',true);
echo $smtp->send('username@darkend.net','user1@darkend.net; user2@darkend.net','标题','<b>内容</b>','附件1.rar; 附件2.rar') ? '发送成功' : '发送失败';

*/
/*
 * PHP 邮件发送类
 * @author 夜末(Darkend)
 * @copyright Dakend.All Rights Reserved.
 * @link www.darkend.net
 * @version 1.0
 */
class mail{
	
	private $server,$user,$pass,$info;
	
	function __construct($server,$user=null,$pass=null,$info=false){
		set_time_limit(0);
		date_default_timezone_set('PRC'); //邮件时区
		$this->coding = 'GB2312'; //邮件编码
		$this->user = $user;
		$this->pass = $pass;
		$this->info = $info;
		$server = explode(':',$server);
		$server[1] = count($server)==1 ? 25 : $server[1];
		$this->sock = fsockopen($server[0],$server[1]);
	}
	
	/* 发送邮件 */
	function send($from,$to,$title,$content,$attachment=null){
		//初始化信息
		$title = base64_encode($title);
		$content = base64_encode($content);
		$attachment!='' && $attachment = explode(';',trim(trim($attachment),';'));
		$to!='' && $to = explode(';',trim(trim($to),';'));
		//开始SMTP会话
		echo $this->info ? '<br />SMTP会话:<br />' : null;
		fgets($this->sock);
		$this->request('HELO localhost');
		$this->user!='' && $this->request('AUTH LOGIN '.base64_encode($this->user));
		$this->pass!='' && $this->request(base64_encode($this->pass));
		$this->request("MAIL FROM:<$from>");
		foreach($to as $value){ $this->request("RCPT TO:<".trim($value).">"); }
		$this->request('DATA');
		$to = implode(',',$to);
		fputs($this->sock,"Date:".date("r")."\r\nFrom:{$from}\r\nTo:{$to}\r\nSubject: =?{$this->coding}?B?{$title}?=\r\nMessage-ID: <".time().rand(0,999).">\r\nMime-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"=====Darkend.net=====\"\r\n\r\nThis is a multi-part message in MIME format.\r\n\r\n--=====Darkend.net=====\r\nContent-Type: text/html; charset=\"{$this->coding}\"\r\nContent-Transfer-Encoding: base64\r\n\r\n{$content}\r\n");
		//发送附件
		if($attachment!=''){
			foreach ($attachment as $value){
				$value = trim($value);
				$fileName = iconv('UTF-8','GB2312',$value); //转换编码解决UTF-8页面不支持中文文件名
				$file = fopen ($fileName, "r");
				$fileData = chunk_split(base64_encode(fread($file,filesize($fileName))));
				$fileName = base64_encode($value);
				fclose($file);
				fputs($this->sock,"\r\n--=====Darkend.net=====\r\nContent-Type: application/octet-stream; name=\"=?{$this->coding}?B?{$fileName}?=\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"=?{$this->coding}?B?{$fileName}?=\"\r\n\r\n{$fileData}\r\n\r\n");
			}
		}
		fputs($this->sock,"--=====Darkend.net=====--\r\n\r\n");
		return strstr($this->request("."),'250') ? true : false;
		$this->request("QUIT");
	}
	
	 /* 处理SMTP对话 */
	function request($request){
		fputs($this->sock,$request."\r\n");
		$sendInfo = fgets($this->sock);
		echo $this->info ? ' > '.$request.'<br />'.$sendInfo.'<br />' : null;
		return $sendInfo;
	}
	
	function __destruct(){
		fclose($this->sock);
		unset($this->server,$this->user,$this->pass,$this->coding,$this->info,$this->sock);
	}
}

?>