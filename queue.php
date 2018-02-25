<?php

	//$rootPath = dirname(_FILE_);
	//echo $rootPath;

	require './PHPMailer/PHPMailerAutoload.php';

	function sendMail($host,$fromEmail,$fromPwd,$fromName,$toEmail,$toName,$subject,$content){

		$mail = new PHPMailer;
		$mail->isSMTP();                       // 使用SMTP发送  
		$mail->Host = $host;                   // 邮件服务器地址
		$mail->SMTPAuth = true;                // 开启SMTP认证  
		$mail->CharSet = "utf-8";              // 定义邮件编码 
		$mail->Encoding = "base64";            // base64加密
		$mail->Username = $fromEmail;          // 登陆SMTP用户名  
		$mail->Password = $fromPwd;            // 登陆SMTP密码
		$mail->From = $fromEmail;              // 发件人邮箱  
		$mail->FromName = $fromName;           // 发件人姓名  
		$mail->addAddress($toEmail, $toName);  //接收者
		$mail->SMTPSecure = "ssl";// 使用ssl协议方式  
		$mail->Port = 994;// 163邮箱的ssl协议方式端口号是465/994  
		$mail->isHTML(true);  
		$mail->Subject = $subject;  
		$mail->msgHTML($content);
		return $mail->send();
	}

	$conn = mysql_connect('localhost','root','rootroot');
	if(! $conn )
	{
	    die('Could not connect: ' . mysql_error());
	}
	echo '数据库连接成功！';

	mysql_select_db('imooc');
	mysql_query('set names utf8');

	while (true) {
		$sql = "SELECT * FROM task_list WHERE status= 0 LIMIT 5";
		$res = mysql_query($sql);
		//var_dump($res);die;

		$mailList = array();
		while ($row = mysql_fetch_assoc($res)) {
			$mailList[] = $row;
		}
		//var_dump($mailList);die;

		if(empty($mailList)){
			break;
		}else{
			foreach ($mailList as $k => $v) {
				if(sendMail('smtp.163.com','hezhan_web@163.com','hezhan123456','giant',$v['user_email'],'加藤非','这是主题','这是内容')){
					mysql_query("update task_list set status=1 where task_id =".$v['task_id']);
				}
				sleep(3);
			}		
		}	
	}
	echo 'done';