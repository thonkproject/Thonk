<?php
session_start();
require 'config.php';
	//connect to thonk db

	if (!isset($_SESSION['username']))
		header('Location: login.php');
	$db = connect_thonkdb();

	//category id array
	$arr = get_category_id_array($db);

	if(isset($_GET['id']))
		$id = $_GET['id'];
	else header('Location: start_node.php');

	$tag_arr = get_tag_from_id($db,$id);
	$_SESSION['parent_id'] = $_GET['id'];

?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Node</title>
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

		@media only screen and (max-device-width: 480px){input[type="text"],input[type="password"],select{width:100%;}input[type="button"],input[type="submit"]{min-width:50px;}#main{width:100%;}}
		@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){ input[type="text"],input[type="password"],select,textarea{width:80%;max-width:600px;}}
		@media only screen and (min-device-width: 1025px){ input[type="text"],input[type="password"],select,textarea{width:80%;max-width:600px;}#node_info{max-width:1000px;margin:0 auto;}}

		h1{color:#77903D;font-weight: bolder;font-size:1.5em;text-align: center;
			padding-bottom: 0.5em;}

		h2{color:rgba(88, 88, 91, 0.75);}
		#main{padding-left:10px;}
		#node_info
		{width: 98%;border: 1px solid black;padding: 30px;border-radius:5px;margin:0 auto;}
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

		#error_prompt
		{
			color:red;
			font-size: 1.0em;
		}
		label{font-weight:bold;}
		select option{color:rgba(88, 88, 91, 0.75);}

	</style>
	<script>
	//
	function validate()
	{
		var error = 0;
		document.getElementById('error_prompt').innerHTML = "";
		var title = document.getElementById('input_title').value;
		var category = document.getElementById('ddl_category').value;


		if (title.replace(/\s/g, '') == '')
		{
			document.getElementById('error_prompt').innerHTML += "*Title must not be blank<br>";
			error++;
		}
		if (category == '')
		{
			document.getElementById('error_prompt').innerHTML += "*Please select a category<br>";
			error++;
		}

		if (error == 0)
			return true;
		return false;

	}
</script>
</head>
<body class="higher-line landing">
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
		<h1>Add Node</h1>

		<form id="form_insert" name="form_insert" onsubmit="return validate()" method="POST" action="addNodeProcess.php">
		Title: <input type="text" id="input_title" name="input_title" placeholder="Node Title">
		<br>Parent Node:
		<input type="hidden" name="parent_id" value="<?php echo $id; ?>"/>
		<input type="text" id="parent_node" name="parent_node" value="<?php echo get_title_from_id($db,$id); ?>" disabled/>
		<br>Category: 
		<select id="ddl_category" name="ddl_category">
				<option value="">Select Category...</option>
			<?php
				foreach($arr as $a)
					echo '<option value="' . $a . '">' . get_category_name($db,$a) . '</option>';
			?>
		</select>

		<span id="space"></span>
		<label id="lb_synopsis" class="hide">Synopsis:</label><span id="space"></span>
		<textarea class="hide" id="input_synopsis" cols="40" rows="5" name="synopsis"></textarea>
		<span id="space"></span>
		<label id="lb_URL" class="hide">Source URL:</label>
		<input type="text" id="input_source_URL" name="source_url" class="hide" placeholder="Source URL"/>
		<span id="space"></span>
		<label id="lb_URL" class="hide">Image URL:</label>
		<input type="text" id="input_image_URL" name="image_url" class="hide" placeholder="Image URL"/>
		<span id="space"></span>
		<label id="lb_URL" class="hide">Video URL:</label>
		<input type="text" id="input_video_URL" name="video_url" class="hide" placeholder="Video URL"/>		
		<span id="space"></span>
		Tags/Keywords: 
		<input type="text" name="tags" id="tags" class="tags" />

		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
		<script src="./tagsinput/jquery.tagsinput.js"></script>
		<link rel="stylesheet" type="text/css" href="./tagsinput/jquery.tagsinput.css" />
		<script>
				//tags styling
			$('#tags').tagsInput({
				'interactive' : true,
				'removeWithBackspace' : true,
				'delimiter': [','],
				
			});

				//loading existing tags
			var tag_array = <?php echo json_encode($tag_arr); ?>;
			for (var i = 0 ; i < tag_array.length; i++)
			{
				$('#tags').addTag(tag_array[i]);
			}
		</script>


		<br>
		<input type="submit" name="btn_submit" class="special button" value="Submit" id="btn_submit" />
		<br><span id="error_prompt"></span>
		</form>
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
<script>
	$('#ddl_category').change(function(){
		if (this.value != 0 && this.value != '' && this.value != null)
		{
			//$("#input_source_URL,#input_video_URL,#input_image_URL,#lb_URL").show();
			$("#space,#input_source_URL,#input_video_URL,#input_image_URL,#lb_URL,#lb_synopsis,#input_synopsis").removeClass('hide');
		}
		else
		{
			//$("#input_source_URL,#input_video_URL,#input_image_URL,#lb_URL").hide();
			$("#space,#input_source_URL,#input_video_URL,#input_image_URL,#lb_URL,#lb_synopsis,#input_synopsis").addClass('hide');
		}
	});
</script>
</html>