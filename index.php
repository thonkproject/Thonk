<?php
    session_start();
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Thonk.org</title>
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
			@media only screen and (max-device-width: 480px) {#header_logo{width:300px;}}
			@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){#header_logo{width:400px;}}
			@media only screen and (min-device-width: 1025px){#header_logo{width:400px;}}
			a img,span,a,img,a span img{text-decoration: none !important;border:0;}
			
		</style>
	</head>
	<body class="landing">

		<!-- Header -->
			<header id="header">
				<h1 id="logo"><a href="./"><img id="img_logo" src="./media/images/logo-02.png" width="150px"></a></h1>
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
						<?php
							if(isset($_SESSION['username']))
							{
								include 'config.php';
								$db = connect_thonkdb();
								if(get_privilege_from_username($db,$_SESSION['username']) != 0)
								{
									echo '<li><a href="./manageuser.php">User Manager</a></li>';
									echo '<li><a href="./managenode.php">Node Manager</a></li>';
								}
							}
						?>
						<li><a href="./start_node.php">View Nodes</a></li>
                        						
                                                						<li>
							<a href="">Info</a>
							<ul>
								<li><a href="./404.html">Search</a></li>
								<li><a href="./FAQ.php">FAQ</a></li>
								<li><a href="./rules.php">Rules</a></li>
								<li><a href="./contact.php">Contact</a></li>
							
							</ul>
                            
                            <li>
							<a href="./userSettings.php">Account</a>
						</li>
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

		<!-- Banner -->
			<section id="banner">
				<div class="content">
					<header>
                    <img id="header_logo" src="./media/images/logo-02.png">
					  <p>A user-friendly map of science that provides a clear<br> visual representation of consensus on issues and<br> context of how they fit into their scientific fields.</p>
					</header>
					<a href="./start_node.php"><span class="image"><img src="http://files.artfly.com/green.png" alt="" /></span></a>
                    
                  <br><br><br><br><span class="ad">AD</span>
                    
			  </div>
			</section>


		<!-- Footer -->
			<footer id="footer">
            
			  <ul class="icons">
					<li><a href="./"><img src="./media/images/logo-02.png" width=150px;></a></li><br><li><a href="./" title="Home" class="icon alt fa-home"><span class="label">Home</span></a></li><li><a href="https://twitter.com/thonk" title="Twitter" class="icon alt fa-twitter" target="new"><span class="label">Twitter</span></a></li>
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