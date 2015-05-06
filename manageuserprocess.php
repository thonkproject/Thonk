<?php
session_start();
require 'config.php';
	//connect to thonk db
	$db = connect_thonkdb();
	if (!isset($_SESSION['username']))
		header('Location: login.php');
	else if(get_privilege_from_username($db,$_SESSION['username']) == 0)
	{header('Location: index.php');}


	if(isset($_GET['user']))
		$username = $_GET['user'];
	else header('Location: manageuser.php');

?>
<!DOCTYPE html>
<html>
<head>
	<title>User Manager</title>
	<meta charset="UTF-8">
	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	<script type="text/javascript">
		<!--
		function win(){
		window.opener.location.href="manageuser.php";
		self.close();
		//-->
		}
	</script>
	<style>
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



		.container
		{
			position:relative;
			border: solid 1px black;
			width:500px;
			padding:30px;
			margin:0 auto;
			color:green;
		}

		h1
		{
			text-align: center;
			padding-bottom: 0.5em;
		}

	</style>

</head>
<body class="higher-line">
	<?php	
		if(isset($_POST['btn_submit']))
		{
			if (get_privilege_from_username($db, $_SESSION['username']) == 2)
				update_privilege($db,$username,htmlspecialchars($_POST['priv']));
			update_status($db,$username,htmlspecialchars($_POST['status']));

			echo '<script>win()</script>';
		}
	?>
	<div class="container">
		<h1>User Manager</h1>

		<form id="form1" name="form1" method="POST">
		<table>
			<tr>
				<td>Username:</td> 
				<td><input type="text" id="username" required readonly name="username" value="<?php echo $username; ?>"></td>
			</tr>
			<tr>
				<td>Email:</td> 
				<td><input type="text" id="email" name="email" required readonly value="<?php echo get_email_from_username($db,$username); ?>"></td>
			</tr>
			<tr>
				<td>Privilege:</td>
				<td>
					<?php
						if (get_privilege_from_username($db, $_SESSION['username']) == 2)
						{
							echo '<select name="priv" id="priv">';
								$priv_arr = get_privilege_id_array($db);
								foreach($priv_arr as $elem)
								{
									echo '<option value="' . $elem . '">' ;
									echo  get_user_privilege_name($db,$elem) .'</option>';
								}
							echo '</select>';
						}
						else
						{
							echo '<input type="text" id="priv" required disabled value="' . get_user_privilege_name($db,get_privilege_from_username($db,$username)) . '">';
						}
					?>					
				</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>
					<?php
						$stat = get_status($db,$username);
						echo '<select name="status" id="status">';
						echo '<option value="active" ';
						if ($stat == "active")
							echo "selected";
						echo '>active</option>'; 

						echo '<option value="inactive" ';
						if ($stat == "inactive")
							echo "selected";
						echo '>inactive</option>';

						echo '<option value="banned" ';
						if ($stat == "banned")
							echo "selected";
						echo '>banned</option>'; 
						echo '</select>';

					?>			
				</td>
			</tr>
			<tr>
				<td><input type="submit" value="Submit" name="btn_submit" id="btn_submit"/></td>
				<td><input type="button" onclick="window.close()" value="Close"/></td>
			</tr>
		</table>
		
		</form>

	</div>



</body>


</html>