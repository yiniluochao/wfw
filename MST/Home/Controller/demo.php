<?php
/*
 * mail.clas.php �ʼ����������ʾ��
 */

/* ��ʼ��  */
require_once 'mail.class.php';

$server = 'smtp.darkend.net';  //SMTP��������ַ
$user = 'demo';  //SMTP�����ʺ�
$pass = 'demo';  //SMTP��������
$info = false;  //�Ƿ���ʾ������Ϣ
$from = 'from@darkend.net';  //������
$to = 'to@darkend.net';  //�ռ���
$title = 'Hello World!';  //����
$content = '<b>�ʼ����ݲ���</b>';  //����
$accessory = null; //���� ( ����·�����ļ���,��������÷ֺŷָ���,����: file1.rar;file2.zip )

// ʵ������,����ʼ��SMTP��������Ϣ
$smtp = new mail($server,$user,$pass,$info); 

//�����ʼ�,������ͳɹ�����true ����ʧ���򷵻�flase
$send = $smtp->send($from,$to,$title,$content,$accessory);

//�ж��Ƿ��ͳɹ�
if($send){
	echo '<h2>�����ʼ��ɹ�</h2>';
}
else{
	echo '<h2>�����ʼ�ʧ��</h2>';
}
?>
