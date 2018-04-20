<?php
/*
 * mail.clas.php 邮件发送类调用示例
 */

/* 初始化  */
require_once 'mail.class.php';

$server = 'smtp.darkend.net';  //SMTP服务器地址
$user = 'demo';  //SMTP服务帐号
$pass = 'demo';  //SMTP服务密码
$info = false;  //是否显示调试信息
$from = 'from@darkend.net';  //发件人
$to = 'to@darkend.net';  //收件人
$title = 'Hello World!';  //标题
$content = '<b>邮件内容测试</b>';  //内容
$accessory = null; //附件 ( 包含路径和文件名,多个附件用分号分隔开,例如: file1.rar;file2.zip )

// 实例化类,并初始化SMTP服务器信息
$smtp = new mail($server,$user,$pass,$info); 

//发送邮件,如果发送成功返回true 发送失败则返回flase
$send = $smtp->send($from,$to,$title,$content,$accessory);

//判断是否发送成功
if($send){
	echo '<h2>发送邮件成功</h2>';
}
else{
	echo '<h2>发送邮件失败</h2>';
}
?>
