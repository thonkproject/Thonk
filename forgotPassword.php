<?php
	session_start();
	include 'config.php';
	$db = connect_thonkdb();
?>


<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.scrolly.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/jquery.scrollex.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
	
	<style>
		@media only screen and (max-device-width: 480px) {#main{width:90%}input[type="text"],input[type="password"]{width:99%;}input[type="submit"]{width:50%;}#suggestion{font-size:0.5em;}}
		@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){ input[type="text"],input[type="password"]{width:80%;}
		#main{width:60%;margin:0 auto;padding-left:10%;padding-top:5%;padding-bottom:5%;}#suggestion{font-size:0.7em;}}
		@media only screen and (min-device-width: 1025px){ input[type="text"],input[type="password"]{width:80%;}
		#main{width:50%;margin:0 auto;padding-left:8%;padding-top:5%;padding-bottom:5%;}#suggestion{font-size:0.9em;}}

		h1{color:#77903D;font-weight: bold;font-size:1.5em;}
		/*#main{width:40%;margin:0 auto;}*/
		.error{color:red;}
	</style>
	<script type="text/javascript">


	function validate()
	{
		var count = 0;
		var str_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		var email = document.getElementById("email").value;
		var username = document.getElementById("username").value;
		document.getElementById("email_error").innerHTML = "";
		document.getElementById("username_error").innerHTML = "";
		document.getElementById("prompt").innerHTML = "";
		if (str_email.test(email) == false)
		{
			count++;
			document.getElementById("email_error").innerHTML = "*Invalid Email format";
		}
		if (email.length === 0)
		{
			count++;
			document.getElementById("email_error").innerHTML = "*Please fill in your email";
		}

		if(username.length === 0)
		{
			count++;
			document.getElementById("username_error").innerHTML = "*Please fill in your username";
		}

		if (count == 0)
			return true;
		return false;

	}
	</script>

</head>
<body>
<!-- Header -->
			<header id="header">
				<h1 id="logo"><a href="./"><img src="http://sdn-thonktest.rhcloud.com/media/images/logo-02.png" width=150px;></a></h1>
				<nav id="nav">
					<ul>
						<li>
							<?php
								if(isset($_SESSION['username']))
									echo '<a href="userSettings.php">Logged in as ' . htmlspecialchars($_SESSION['username']) .'</a>';
								else echo '<a href="login.php">You are not logged in</a>';
							?>
						</li>
						<li><a href="./">Home</a></li>

						<li><a href="./start_node.php">View Nodes</a></li>
                        						
                           <li><a href="">Info</a>
							<ul>
								<li><a href="./404.html">Search</a></li>
								<li><a href="./FAQ.php">FAQ</a></li>
								<li><a href="./rules.php">Rules</a></li>
								<li><a href="./contact.php">Contact</a></li>
							
							</ul></li>
                            <li><a href="./userSettings.php">Account</a></li>
						<li>
						<?php
							if(isset($_SESSION['username']))
								echo '<a href="./logout.php" class="button special">Sign Out</a>';
							else echo '<a href="./login.php" class="button special">Sign In</a>'
						?>
						</li>
					</ul>
				</nav>
			</header>
	<div class="wrapper" id="main">
		<h1>Forgot Password</h1>
	<form id="form1" onsubmit="return validate()" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<label>Your username</label>
				<input type="text" name="username" id="username">
				<span id="username_error" class="error"></span>
				<br>
				<label>Your Email</label>
				<input type="text" name="email" id="email">
				<span id="email_error" class="error"></span>
				<br>
				<input type="submit" name="btn_submit" class="button special" value="Get New Password">
				<br>
				<span id="prompt" class="error"></span>
	</form>
	</div>
	<?php
		if(isset($_POST['btn_submit']))
		{
			$username = htmlspecialchars($_POST['username']);
			$email = htmlspecialchars($_POST['email']);

			if(find_username($db,$username) == false)
			{
				echo '<script type="text/javascript">';
				echo 'document.getElementById("prompt").innerHTML = "*Username doesn not exist"';
				echo '</script>';
			}
			else if(get_email_from_username($db,$username) != $email)
			{
				echo '<script type="text/javascript">';
				echo 'document.getElementById("prompt").innerHTML = "*Incorrect email address"';
				echo '</script>';
			}
			else
			{
				//change password here
				$newPass = randomPassword();
				update_password($db,$username,$newPass);
				//send email here
				require 'mailer/PHPMailer-master/PHPMailerAutoload.php';
				$mail = new PHPMailer;

				 $email = 'thonktest@gmail.com';
				 $subject = "New Thonk Password";
				$message = '<html><head><title>New Password</title></head><body><p>Username: ' . $username .'</p><p>New Password: ' . $newPass . '</p></body></html>';
					
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.sendgrid.net';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'zeroshinoda';                 // SMTP username
				$mail->Password = 'greenday1';                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to

				$mail->From = $email; //address of sender
				$mail->FromName = 'ThonkAdmin'; //subject
				$mail->addAddress(get_email_from_username($db,$username), $username);     // Add a recipient
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




				//prompt here
				echo '<script type="text/javascript">';
				echo 'document.getElementById("prompt").innerHTML = "*A temporary password has been sent to your email<br>Please change your password as soon as you login"';
				echo '</script>';
			}
		}
	?>
			<!-- Footer -->
			<footer id="footer">
            
			  <ul class="icons">
					<li><a href="./"><img src="http://sdn-thonktest.rhcloud.com/media/images/logo-02.png" width=150px;></a></li><br><li><a href="./" title="Home" class="icon alt fa-home"><span class="label">Home</span></a></li><li><a href="https://twitter.com/thonk" title="Twitter" class="icon alt fa-twitter" target="new"><span class="label">Twitter</span></a></li>
					<li><a href="https://www.facebook.com/pages/THONK/258177714363949?fref=ts" title="Facebook" class="icon alt fa-facebook" target="new"><span class="label">Facebook</span></a></li>
					<li><a href="./contact.php" title="Email" class="icon alt fa-envelope" target="new"><span class="label">Email</span></a></li>
                    <li><a href="https://www.indiegogo.com/projects/thonk" title="Donate" class="icon alt fa-credit-card" target="new"><span class="label">Donate</span></a></li><li><a href="./404.html" title="Search" class="icon alt fa-search" target="new"><span class="label">Search</span></a></li>
				</ul>
				<ul class="copyright">
					<li>&copy; Thonk 2015. All rights reserved.</li>
				</ul>
			</footer>
</body>
</html>