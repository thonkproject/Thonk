<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<title>Frequently Asked Questions</title>

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
	<style>
		h1{color:#77903D;font-weight: bold;font-size:1.8em;}
		#main{width:70%;margin:0 auto;font-size:0.8em;}
		.q{color:red;}
		.a{color:green;}
		span a {font-weight: bolder;color:#77903D;}
		.fs, .fsad{border:0;padding-left:40px;}
		.container2{padding-left:20px;}
		.container{font-size: 16px;}
		.container h1{font-size:1.8em;}
		.fsad{border: 1px solid; padding:10px;}
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
		<h1>Frequently Asked Questions</h1>
		<?php
			$xml=simplexml_load_file("FAQ.xml") or die("Error: Cannot create object");
			$order = 0;		
			for($i = 0; $i < sizeof($xml->FAQ); $i++) {
				echo '<fieldset class="fs">';
					//echo "<td>" . $xml->FAQ[$i]->ORDER . "</td>";
					echo '<h3 class="q">Q</h3> <span class="questions">' . $xml->FAQ[$i]->QUESTION . "</span>";
					echo '<h3 class="a">A</h3><span class="answers">' . $xml->FAQ[$i]->ANSWER . "</span>";
				echo "</fieldset><br>";
				$order = $i + 2;
			}

			

		if(isset($_SESSION["username"]))
		{		
			include 'config.php';
			$db = connect_thonkdb();
			if(get_privilege_from_username($db,$_SESSION["username"]) == 1)
			{
				if (isset($_POST["btnSubmit"]) ) {
					$question = $_POST["question"];
					$answer = $_POST["answer"];
				
					$FAQ = $xml->addChild('FAQ');
					$FAQ->addChild('ORDER', $order);
					$FAQ->addChild('QUESTION', $question);
					$FAQ->addChild('ANSWER', $answer);
					file_put_contents('FAQ.xml', $xml->asXML());
					header('Location: FAQ.php');
				}	
		?>
		<fieldset class="fsad">
		<h1>Post New Q&A</h1>
		<form id="form1" name="form1"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<h3>Add a new FAQ entry:</h3>

				<label>Question</label>
				<textarea name="question" type="text" required></textarea>
			
			<label>Answer</label>
			<textarea name="answer" type="text" required></textarea>
			<br>
			<input name="btnSubmit" type="submit" value="Post" class="button special"/>
						
		</form>
		</fieldset>
	</div>
	<?php
		}}//end isset(SESSION["username"])
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
</html>