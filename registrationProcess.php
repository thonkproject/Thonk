<?php
session_start();
require 'config.php';
//connect to thonk db
	if(!isset($_GET['or']))
		header('Location: ./');
	$db = connect_thonkdb();
	$id = htmlspecialchars($_GET['or']);
	$usern = get_username_from_id($db,$id);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
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
		@media only screen and (max-device-width: 480px) {#main{width:100%;margin:0 auto;line-height: 2em;text-align: center;padding-top:10%;padding-bottom:10%;}}
		@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){#main{width:90%;margin:0 auto;line-height: 2em;text-align: center;padding-top:15%;padding-bottom:15%;}}
		@media only screen and (min-device-width: 1025px){#main{width:80%;margin:0 auto;line-height: 2em;text-align: center;padding-top:15%;padding-bottom:15%;}}
		
		#congrat{
			font-size: 2em;
		}

		#notification
		{
			font-size: 1.5em;
		}
		#name
		{
			font-weight: bold;
			color:#77903D;;
		}

		#link
		{
			color:blue;
		}
	</style>

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
		<span id="congrat">Congratulation, <span id="name"><?php echo $usern; ?></span>!<br>You have successfully registered to <span id="name">Thonk</span>...</span>
		<br>
		<span id="notification">We are sending you an email to activate your account...<br></span>
		<br>
		<a href="login.php" id="link">Go back to Login Page</a>
		<?php

			header("refresh: 10; url=login.php");
		?>
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