<?php
session_start();
require 'config.php';

	if (!isset($_SESSION['username']))
		header('Location: login.php');

	//connect to thonk db
	$db = connect_thonkdb();
	$title = htmlspecialchars($_POST['input_title']);
	$parent_id = htmlspecialchars($_POST['parent_id']);
	$category_id = htmlspecialchars($_POST['ddl_category']);
	$source_url = htmlspecialchars($_POST['source_url']);
	$video_url = htmlspecialchars($_POST['video_url']);
	$image_url = htmlspecialchars($_POST['image_url']);
	$synopsis = htmlspecialchars($_POST['synopsis']);
	$creator = get_id_from_username($db,htmlspecialchars($_SESSION['username']));
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Node</title>
	 <script src="http://d3js.org/d3.v3.min.js"></script>
  <script src="myscript.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
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

		@media only screen and (max-device-width: 480px){input[type="text"],input[type="password"],select{width:100%;}input[type="button"],input[type="submit"]{padding-left:15px;padding-right:15px;}#main{width:100%;}}
		@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){ input[type="text"],input[type="password"],select,textarea{width:80%;max-width:600px;}}
		@media only screen and (min-device-width: 1025px){ input[type="text"],input[type="password"],select,textarea{width:80%;max-width:600px;}#node_info{max-width:1000px;margin:0 auto;}}

		h1{color:#77903D;font-weight: bolder;font-size:1.5em;text-align: center;
			padding-bottom: 0.5em;}

		#main{padding-left:10px;}
		#node_info
		{width: 98%;padding: 30px;border-radius:5px;margin:0 auto;min-height:420px;}
		}

	</style>
	<script type="text/javascript">
		<!--
		function win(){
		//window.opener.location.href="start_node.php";
		get_id_from_session();
		//-->
		}

		function get_id_from_session(){
				 $.ajax({
	          type: 'POST',
	   
	          url: "config_map.php?f=get_parent_id_from_session&p=" ,
	          success: function(d){
	          	console.log("The map will be refreshed");
	          	alert("The map will be refreshed");
	    		opener.make_json(d);
	    		self.close();
	           }
	        });
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
	<div id="node_info">
	<?php
		$id = insert_node($db,$title,$category_id,$parent_id);
		$tag_arr = get_tag_array(htmlspecialchars($_POST['tags']));
		update_tags($db,$id,$tag_arr);
		update_creator_id($db,$id,$creator);
		update_last_modified($db,$id,$creator);

			if ($category_id != 0)
			{
				update_synopsis($db,$id,$synopsis);
				update_source_url($db,$id,$source_url);
				update_video_url($db,$id,$video_url);
				update_image_url($db,$id,$image_url);
				initialize_child_node($db,$id);
			}


			echo '<span style="color:#77903D;font-size:1em;">Node added successfully!</span><br>';
			echo '<br><input type=button onClick="win();" class="button special" value="Close">';

	?>
	</div>
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