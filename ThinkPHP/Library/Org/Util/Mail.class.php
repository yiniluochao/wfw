<?php
/*
mail������:
mail(string $server,string [ $user,string $pass, boole $Info ])
server: SMTP��������ַ,Ĭ��Ϊ25�˿�,������SMTP����˿ڲ���25,����������ַ�����ð��+�˿�,����: 127.0.0.1:26
user: SMTP�����ʺ� ( ��ѡ, �������������Ҫ��֤���Բ��� )
pass: SMTP�������� ( ��ѡ, �������������Ҫ��֤���Բ��� )
info: �Ƿ���ʾ������Ϣ (��ѡ, Ĭ�ϲ���ʾ������Ϣ )

send(�ʼ�����)����:
boole send(string $from,string $to,string $title,string $content,[ string $attachment ])
from: ������
to: �ռ��� ( ����ռ����÷ֺŸ���,����: i@darkend.net; m@darkend.net )
title: ����
content: ���� (֧��HTML)
attachment: ���� (��ѡ, ����·�����ļ���,��������÷ֺŷָ���,����: file1.rar; file2.zip )

����:
$smtp = new mail("smtp.darkend.net",'username','password',true);
echo $smtp->send('username@darkend.net','user1@darkend.net; user2@darkend.net','����','<b>����</b>','����1.rar; ����2.rar') ? '���ͳɹ�' : '����ʧ��';

*/
/*
 * PHP �ʼ�������
 * @author ҹĩ(Darkend)
 * @copyright Dakend.All Rights Reserved.
 * @link www.darkend.net
 * @version 1.0
 */
class mail{
	
	private $server,$user,$pass,$info;
	
	function __construct($server,$user=null,$pass=null,$info=false){
		set_time_limit(0);
		date_default_timezone_set('PRC'); //�ʼ�ʱ��
		$this->coding = 'GB2312'; //�ʼ�����
		$this->user = $user;
		$this->pass = $pass;
		$this->info = $info;
		$server = explode(':',$server);
		$server[1] = count($server)==1 ? 25 : $server[1];
		$this->sock = fsockopen($server[0],$server[1]);
	}
	
	/* �����ʼ� */
	function send($from,$to,$title,$content,$attachment=null){
		//��ʼ����Ϣ
		$title = base64_encode($title);
		$content = base64_encode($content);
		$attachment!='' && $attachment = explode(';',trim(trim($attachment),';'));
		$to!='' && $to = explode(';',trim(trim($to),';'));
		//��ʼSMTP�Ự
		echo $this->info ? '<br />SMTP�Ự:<br />' : null;
		fgets($this->sock);
		$this->request('HELO localhost');
		$this->user!='' && $this->request('AUTH LOGIN '.base64_encode($this->user));
		$this->pass!='' && $this->request(base64_encode($this->pass));
		$this->request("MAIL FROM:<$from>");
		foreach($to as $value){ $this->request("RCPT TO:<".trim($value).">"); }
		$this->request('DATA');
		$to = implode(',',$to);
		fputs($this->sock,"Date:".date("r")."\r\nFrom:{$from}\r\nTo:{$to}\r\nSubject: =?{$this->coding}?B?{$title}?=\r\nMessage-ID: <".time().rand(0,999).">\r\nMime-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"=====Darkend.net=====\"\r\n\r\nThis is a multi-part message in MIME format.\r\n\r\n--=====Darkend.net=====\r\nContent-Type: text/html; charset=\"{$this->coding}\"\r\nContent-Transfer-Encoding: base64\r\n\r\n{$content}\r\n");
		//���͸���
		if($attachment!=''){
			foreach ($attachment as $value){
				$value = trim($value);
				$fileName = iconv('UTF-8','GB2312',$value); //ת��������UTF-8ҳ�治֧�������ļ���
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
	
	 /* ����SMTP�Ի� */
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