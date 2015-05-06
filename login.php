<?php
	session_start();
	include 'config.php';
	$db = connect_thonkdb();
	if(isset($_SESSION['username']))
		{ header('Location: userSettings.php');}
?>
<!DOCTYPE html>
<html>
<head>       
	<title>Login</title>
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
		@media only screen and (max-device-width: 480px) {#main{width:90%}input[type="text"],input[type="password"]{width:99%;}input[type="submit"]{width:20%;}#suggestion{font-size:0.3em;}}
		@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){ input[type="text"],input[type="password"]{width:80%;}
		#main{width:60%;margin:0 auto;padding-left:10%;padding-top:14%;padding-bottom:7%;}#suggestion{font-size:0.7em;}}
		@media only screen and (min-device-width: 1025px){ input[type="text"],input[type="password"]{width:80%;}
		#main{width:50%;margin:0 auto;padding-left:10%;padding-top:7%;padding-bottom:7%;}#suggestion{font-size:0.9em;}}
			
		h1{color:#77903D;font-weight: bold;font-size:1.5em;}

		#signup_link
		{color:black;}
		#signup_link:hover{font-weight:bold;color:#77903D;}
		.error{font-size:0.7em;color:red;}
		.confirm
		{color:red;font-weight: bold;}
	</style>
	<script type="text/javascript">
		function validate()
		{
			var count = 0;
			document.getElementById("pw_error").innerHTML = "";
			document.getElementById("username_error").innerHTML = "";

			var username = document.getElementById("username").value;
			var pw = document.getElementById("password").value;

			if (pw.length == 0 || pw == "")
			{
				count++;
				document.getElementById("pw_error").innerHTML = "*Please fill in your password"; 
			}
			if (username.length == 0 || username == "")
			{
				count++
				document.getElementById("username_error").innerHTML = "*Please fill in your username"; 
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
		<form id="form_login" method="POST" onsubmit="return validate()" action="<?php echo $_SERVER['PHP_SELF']; ?>"/>
		<h1>Login</h1>

				<label>Username</label>
				<input type="text" id="username" name="username" class="input_text">
				<span id="username_error" class="error"></span>
			
			<label>Password</label>
				<input type="password" id="password" name="password" class="input_text">
				<span id="pw_error" class="error"></span>
				<br>
				<input type="submit" class="button special" name="btn_submit" value="Login">
				<a id="signup_link" href="forgotPassword.php">Forgot password?</a> or <a id="signup_link" href="registration.php">Signup here!</a>
			
			<?php
			 if((isset($_POST['btn_submit'])))
			 {
			 	$username = htmlspecialchars($_POST['username']);
			 	$password = htmlspecialchars($_POST['password']);
			 	if (find_username($db,$username) == true)
			 	{
			 		if (check_status($db,$username) == false)
			 		{
			 			echo '<script type="text/javascript">';
						echo 'document.getElementById("username_error").innerHTML = "*User is not active! Contact the administrator ";';
						echo '</script>';
			 		}
			 		else
			 		{
				 		if(login($db,$username,$password) == true)
				 		{
				 			$priv = get_privilege_from_username($db,$username);
				 			if($priv !== 0)
				 			{
				 				$_SESSION['username'] = $username;
				 				header('Location: manageuser.php');
				 			}
				 			else
				 			{
					 			$_SESSION['username'] = $username;
					 			header('Location: start_node.php');
				 			}
				 		}
				 		else
				 		{
				 			echo '<script type="text/javascript">';
							echo 'document.getElementById("pw_error").innerHTML = "*Incorrect password!"';
							echo '</script>';
				 		}
				 	}
			 	}
			 	else
			 	{
			 		echo '<script type="text/javascript">';
					echo 'document.getElementById("username_error").innerHTML = "*Invalid username!"';
					echo '</script>';
			 	}
			 }//end isset
			?>
		</form>
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
