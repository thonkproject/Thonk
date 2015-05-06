<?php
	session_start();
	include 'config.php';
	$db = connect_thonkdb();
	if (isset($_SESSION['username']))
	{
		$username = htmlspecialchars($_SESSION['username']);
	}
	else header('Location: login.php');
	$oldemail = get_email_from_username($db,$username);
?>


<!DOCTYPE html>
<html>
<head>
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
<!-- 		<link rel="stylesheet" href="css/thonk2.css" /> -->

	<title>User Settings</title>
	<style>
		legend{color:#77903D;font-weight: bold;font-size:1.1em;}
		@media only screen and (max-device-width: 480px) {#main{width:98%}input[type="text"],input[type="password"]{width:99%;}input[type="submit"]{width:100%;}#suggestion{font-size:0.5em;}}
		@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){ input[type="text"],input[type="password"]{width:80%;}
		#main{width:60%;margin:0 auto;padding-left:10%;padding-top:14%;padding-bottom:7%;}#suggestion{font-size:0.7em;}}
		@media only screen and (min-device-width: 1025px){ input[type="text"],input[type="password"]{width:80%;}
		#main{width:50%;margin:0 auto;padding-left:10%;padding-top:14%;padding-bottom:7%;}#suggestion{font-size:0.9em;}}
		/*	#main{width:60%;margin:0 auto;}*/
		.error, .confirm{font-size:0.85em;color:red;}
	</style>
	<script type="text/javascript">
	function check_password()
	{
		var count = 0;
		var pw_str = (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/);
		document.getElementById("pw_confirm").innerHTML = "";
		document.getElementById("oldpw_error").innerHTML = "";
		document.getElementById("pw1_error").innerHTML = "";
		document.getElementById("pw2_error").innerHTML = "";

		var oldpw = document.getElementById("oldpw").value;
		var pw1 = document.getElementById("pw1").value;
		var pw2 = document.getElementById("pw2").value;

		if (oldpw.replace(" ","") == "" || oldpw.length === 0)
		{
			count++;
			document.getElementById("oldpw_error").innerHTML = "*Please fill in your current password"; 
		}

		if (pw_str.test(pw1) == false)
		{
			count++;
			document.getElementById("pw1_error").innerHTML = "*Password must have at least one digit, one lower case, one upper case"; 
		}

		if (pw1.length > 0 && pw1.length < 8)
		{
			count++;
			document.getElementById("pw1_error").innerHTML = "*Password must have at least 8 characters"; 
		}

		if (pw1 !== pw2)
		{
			count++;
			document.getElementById("pw1_error").innerHTML = "*Passwords don't match"; 
		}
		
		if (pw1.length === 0 )
		{
			count++;
			document.getElementById("pw1_error").innerHTML = "*Please fill in your new password"; 
		}

		if (pw1.length === 0 )
		{
			count++;
			document.getElementById("pw1_error").innerHTML = "*Please fill in your new password"; 
		}

		if (pw2.length === 0 )
		{
			count++;
			document.getElementById("pw2_error").innerHTML = "*Please fill in your repeat password"; 
		}

		if (count == 0)
			return true;
		return false;

	}


	function check_email()
	{
		var count = 0;
		var str_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		var email = document.getElementById("email").value;
		document.getElementById("email_error").innerHTML = "";
		document.getElementById("email_confirm").innerHTML = "";
		if (str_email.test(email) == false)
		{
			count++;
			document.getElementById("email_error").innerHTML = "*Invalid Email";
		}
		if (email.length === 0)
		{
			count++;
			document.getElementById("email_error").innerHTML = "*Please fill in your new email";
		}

		if (count == 0)
			return true;
		return false;

	}
	</script>

</head>
<body class="landing">
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

		<h1>Account Settings</h1>
		<fieldset id="fs_username">
			<legend>Account Information</legend>
				<table id="username_table">
					<tr>
						<td>Username:</td>
						<td><label><?php echo $username; ?></label></td>
						<td><span class="error"></td>
					</tr>
				</table>
		</fieldset>
		<br>
		<fieldset id="fs_password">
			<legend>Update Password</legend>
			<form onsubmit="return check_password()" id="form_password" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<table id="password_table">
					<tr>
						<td>Current Password:</td>
						<td><input type="password" name="oldpw" id="oldpw"/></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2"><span id="oldpw_error" class="error"></span></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="pw1" id="pw1"/></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2"><span id="pw1_error" class="error"></span></td>
					</tr>
					<tr>
						<td>Password Repeat:</td>
						<td><input type="password" name="pw2" id="pw2"/></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2"><span id="pw2_error" class="error"></span></td>
					</tr>
					<tr>
						<td><input type="submit" name="pw_submit" class="button special" value="Update"/></td>
						<td colspan="2"><span id="pw_confirm" class="confirm"></span></td>
							<?php
								if (isset($_POST['pw_submit']))
								{
									$oldpw = htmlspecialchars($_POST['oldpw']);
									$newpw = htmlspecialchars($_POST['pw1']);
									if(get_password_from_username($db,$username) === md5($oldpw))
									{
										if(update_password($db,$username,$newpw) == 0)
										{
											echo '<script type="text/javascript">';
											echo 'document.getElementById("pw_confirm").innerHTML = "Password successfully changed"';
											echo '</script>';
										}
										else
										{
											echo '<script type="text/javascript">';
											echo 'document.getElementById("email_confirm").innerHTML = "*Fail to update password! Please try again later!"';
											echo '</script>';
										}
									}
									else
									{
										echo '<script type="text/javascript">';
										echo 'document.getElementById("pw_confirm").innerHTML = "*Invalid current password!"';
										echo '</script>';
									}
								}//end isset
							?>
					</tr>
				</table>
			</form>
		</fieldset>
		<br>
		<fieldset id="fs_email">
			<legend>Update Email</legend>
			<form id="form_email" onsubmit="return check_email()" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<table id="email_table">
					<tr>
						<td>Old Email:</td>
						<td><input type="text" name="old_email" id="old_email" value="<?php echo $oldemail; ?>" disabled/></td>
						<td></td>
					</tr>
					<tr>
						<td>New Email:</td>
						<td><input type="text" name="email" id="email"/></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2"><span id="email_error" class="error"></span></td>
					</tr>
					<tr>
						<td><input type="submit" name="email_submit" class="button special" value="Update Email"/></td>
						<td colspan="2"><span id="email_confirm" class="confirm"></span></td>
							<?php
								if (isset($_POST['email_submit']))
								{
									$newemail = htmlspecialchars($_POST['email']);
									if(check_email($db,$newemail) == false)
									{
										if(update_email($db,$username,$newemail) == 0)
										{
											$oldemail = get_email_from_username($db,$username);
											echo '<script type="text/javascript">';
											echo 'document.getElementById("old_email").value ="' . $oldemail . '";';
											echo 'document.getElementById("email_confirm").innerHTML = "Email updated!"';
											echo '</script>';
										}
										else
										{
											echo '<script type="text/javascript">';
											echo 'document.getElementById("email_confirm").innerHTML = "*Fail to update email! Please try again later!"';
											echo '</script>';
										}
									}
									else
									{
										echo '<script type="text/javascript">';
										echo 'document.getElementById("email_confirm").innerHTML = "*Email already in use!"';
										echo '</script>';
									}
								}//end isset
							?>
					</tr>
				</table>
			</form>
		</fieldset>
		</div>
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