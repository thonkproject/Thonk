<?php
	if (isset($_GET['u']))
	{
		$user = $_GET['u'];
	}
	else
	{
		header('Location: ../');
	}
	require 'mailer/PHPMailer-master/PHPMailerAutoload.php';
	include 'config.php';
	$db = connect_thonkdb();
	$mail = new PHPMailer;

		$name = get_username_from_id($db,$user);
		$url = 'sdn-thonktest.rhcloud.com/actions/index.php?id=' . $user . '&action=activateuser';
		 $email = 'thonktest@gmail.com';
		 $subject = "Email verification";
		$message = '<html><head><title>Verification Email</title></head><body><a href = "' . $url . '" target="_new">Activate here!</a></body></html>';
			
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.sendgrid.net';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'zeroshinoda';                 // SMTP username
		$mail->Password = 'greenday1';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->From = $email; //address of sender
		$mail->FromName = 'ThonkAdmin'; //subject
		$mail->addAddress(get_email_from_username($db,$name), $name);     // Add a recipient
		//$mail->addAddress('thonktest@gmail.com');               // Name is optional
		$mail->addReplyTo('thonktest@gmail.com', 'Thonk Team');
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');

		$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = $message;

		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
		else
		{header('Location: registrationProcess.php?or=' . $user);}


?>