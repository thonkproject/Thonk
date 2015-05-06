<?php
session_start();
require 'config.php';
	//connect to thonk db
	$db = connect_thonkdb();

		if(isset($_GET['id']))
			$id = $_GET['id'];
		else header('Location: index.php');
		//category id array
	$tag_arr = get_tag_from_id($db,$id);
	//$parent_id = get_parent_from_id($db,$id);
	//echo get_category_name($db,get_category_from_id($db,$id));
	//$cat_id = (int) get_category_from_id($db,$id);
	$thumbup = thumbup_count($db,$id);
	$thumbdown = thumbdown_count($db,$id);

	$_SESSION['parent_id'] = $_GET['parent_id'];

	$refresh = false;

	$up_file="up_btt.jpg";
	$down_file="down_btt.jpg";


	if(isset($_POST['btn_submit'])) //insert comment
	{
		
		insert_comment($db,$id,$_POST['txt_comment'],get_id_from_username($db,$_SESSION['username']));
		echo '<script type="text/javascript">$("#comment").load("viewNode.php?id=' . $id . ' #scores");</script>';
	}

	if(isset($_POST['btn_up'])) //insert comment
	{
		thumbup($db,get_id_from_username($db,$_SESSION['username']),$_GET['id']);
		echo '<script type="text/javascript">$("#voting").load("viewNode.php?id=' . $id . '");</script>';
		$refresh = true;
	}

	if(isset($_POST['btn_down'])) //insert comment
	{
		thumbdown($db,get_id_from_username($db,$_SESSION['username']),$_GET['id']);
		echo '<script type="text/javascript">$("#voting").load("viewNode.php?id=' . $id . '");</script>';
		$refresh = true;
	}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<title>View Node</title>
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="./tagsinput/jquery.tagsinput.js"></script>
	<link rel="stylesheet" type="text/css" href="./tagsinput/jquery.tagsinput.css" />

		<style>
		@media only screen and (max-device-width: 480px){input[type="button"],input[type="submit"]{padding-left:5px;padding-right:5px;}img{max-width:100%;}}
		h1{color:#77903D;font-weight: bolder;font-size:1.5em;text-align: center;
			padding-bottom: 0.5em;}

		h2{color:rgba(88, 88, 91, 0.75);}

		.hide
		{
			display:none;
			visibility: hidden;
		}

		.higher-line
		{
			line-height: 3.0em;
		}

		#space
		{
			display:block;
		}

		#node_info
		{width: 98%;border: 1px solid black;padding: 30px;border-radius:5px;margin:0 auto;}

		label{color:#77903D;}

		.content{color: black;
			-ms-word-break: break-all;
	     word-break: break-all;
	     word-break: break-word;
		-webkit-hyphens: auto;
		   -moz-hyphens: auto;
		        hyphens: auto;
		}
		.lb{font-size: 1.2em;font-weight: bolder;}

		#voting{float:right;margin-right: 10%;font-weight:bolder;font-size: 1.4em;color: black;}
		#thumbup{color:lime;}
		#thumbdown{color:red;}
		
		#comment_box{border: solid 1px;padding:10px;border-radius:5px;}
		#comment_post{border:0;}
		#comment
		{padding:30px;}
		#comment_time
		{
			float:right;			
			font-size:0.7em;
		}
		#comment_desc
		{
			margin-left:5%;
			color:black;
		}
		#comment_user
		{
			font-size:1.3em;
			font-weight:bold;
			color:#77903D;
		}

		#txt_comment 
		{
			width:100%;
			height:100px;
		}

		img{max-width:80%;}

		a {color:blue;font-weight: bolder;}

		#btn_up{
		width: 50px;
		height: 50px;
		}
		#btn_down{
		width: 50px;
		height: 50px;
		}
	</style>

</head>
<body class="higher-line landing">
	<script type="text/javascript">

		function win(){
			self.close();
		}
		<!--
		function win_refresh(){
		//window.opener.location.href="start_node.php";
		get_id_from_session();
		//-->
		}

		function get_id_from_session(){
				 $.ajax({
	          type: 'POST',
	   
	          url: "config_map.php?f=get_parent_id_from_session&p=" ,
	          success: function(d){
	          	console.log(d);
	          	alert("The map will be refreshed.");
	    		opener.make_json(d);
	    		self.close();
	           }
	        });
		}
	</script>

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
				if($refresh == true)
					echo '<input type=button onClick="win_refresh();" value="Close" class="button special">';
				else
					echo '<input type=button onClick="win();" value="Close" class="button special">';

			?>

			<h1><?php echo get_title_from_id($db,$id); ?></h1>
			<div id="voting">
							Like<span id="thumbup"><?php echo " " . $thumbup; ?></span>
							Dislike<span id="thumbdown"><?php echo " " . $thumbdown; ?></span>
			</div>
			<?php
				if(isset($_SESSION['username']))
				{ 
					if(check_thumbup($db,get_id_from_username($db,$_SESSION['username']),$_GET['id']) == true)
					{
						echo "<h2>Your rating:  Support</h2>";
						$up_file="up_btt_clicked.jpg";
						$down_file="down_btt.jpg";

					}
					else if(check_thumbdown($db,get_id_from_username($db,$_SESSION['username']),$_GET['id']) == true)
					{	
						echo "<h2>Your rating: Discredit</h2>";
						$down_file="down_btt_clicked.jpg";
						$up_file="up_btt.jpg";

					}
					else
						echo "<h2>Your rating: none</h2>";
				}

			?>
			


			<h2 style="display:inline-block">Rate this node:</h2>
			<form style="display:inline-block" name="rating_form" action="" method="post">
				<input type="image" src="<?php echo $up_file ?>" value="Support" name="btn_up" id="btn_up" />&nbsp
				<input type="image" src="<?php echo $down_file ?>" value="Discredit" name="btn_down" id="btn_down"/>
			</form>
		
			
			<span id="space"></span>
			<label class="lb" >Synopsis:</label>		
			<span id="space"></span>
			<label class="content"><?php echo get_synopsis_from_id($db,$id); ?></label>
			<span id="space"></span>
			<label class="lb">Source URL:</label>
			<a href="<?php echo get_sourceurl_from_id($db,$id); ?>" class="content"><?php echo get_sourceurl_from_id($db,$id); ?></a>
			<span id="space"></span>
			<label class="lb">Image URL:</label>
			<span id="space"></span>
			<img src="<?php echo get_imageurl_from_id($db,$id); ?>" class="content" alt="There is no image available for this node" id="img_src">
			<span id="space"></span>
			<label class="lb">Video URL:</label>
			<?php 
				if (get_videourl_from_id($db,$id) != "")
				{
					echo '<a href="' . get_videourl_from_id($db,$id) . '" class="content">';
					echo get_videourl_from_id($db,$id);
					echo '</a>';
				}
				else echo '<label class="content">There is no video available for this node</label>';
			?>

			<span id="space"></span>
			<label class="lb">Tags/Keywords:</label> 
			<label name="tags" id="tags" class="tags content" ><?php for($i=0; $i< count($tag_arr); $i++){ echo $tag_arr[$i]; if($i != count($tag_arr) - 1) {echo ", ";}} ?></label>
		</div>
		<div id="comment">
		<h3>Comments</h3>
		<?php
			if(isset($_SESSION['username'])) //user can post comment
			{
				echo '<form name="cmt_form" action="" method="post">';
				echo '<fieldset id="comment_post">';
				echo '<legend id="comment_user">@' . $_SESSION['username'] . '</legend>';
				echo '<textarea name="txt_comment" id="txt_comment" placeholder="Write your comment here..." required></textarea>';
				echo '<br><input type="submit" value="Submit" name="btn_submit" class="button special" id="btn_comment"/>';
				echo '</fieldset></form>';

			}

				$cmt_arr = get_comment($db,$id);
				$cmt_arr_reverse = array_reverse($cmt_arr,true);
			//display comments
			foreach($cmt_arr_reverse as $cmt)
			{
				echo '<fieldset id="comment_box">';
				echo '<legend id="comment_user">@' . get_username_from_id($db,$cmt['comment_user_id'])  . '</legend>';
				echo '<span id="comment_desc">' . $cmt['comment_desc'] . '</span><br>';
				echo '<span id="comment_time">' . $cmt['comment_timestamp'] . '</span>';
				echo '</fieldset>';
			}

		?>
					<br>
					<input type=button onClick="win();" class="button special" value="Close">

		</div>
	</div> <!--Container-->
</body>
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

</html>