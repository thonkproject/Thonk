
<?php
session_start();
require 'config.php';
	$db = connect_thonkdb();

	if (!isset($_SESSION['username']))
		header('Location: login.php');
	else if(get_privilege_from_username($db,$_SESSION['username']) == 0)
	{header('Location: ./');}


	$list = $db->user->find();
	$arr = array();
	foreach ($list as $a)
		$arr[] = $a['user_name'];


?>

<!DOCTYPE html>
<html>
<head>
	<title>User Management</title>
	<meta charset="UTF-8">
	<style>
		table, td, th, tr
		{
			border: 1px black solid;
			border-collapse: collapse;
		}
		#nav-bar{position:relative;width:100%;top:0;left:0;}
		ul{list-style-type: none;margin-top:5px;margin-bottom:5px;}
		li{display: inline-block;margin-top:5px;margin-bottom:5px;}
		#nav-bar a{max-width:200px;background-color:#77903D;text-decoration: none;color:black;font-weight:bold;padding: 5px 15px 5px 15px ;border-radius:5px;}
		#nav-bar a:hover{text-decoration: underline;}
	</style>
		<!-- DataTables CSS -->
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="./media/css/jquery.dataTables_themeroller.css">
		<link rel="stylesheet" type="text/css" href="css/thonk2.css">
		<!-- jQuery -->
		
		<script type="text/javascript" charset="utf8" src="./media/js/jquery.js"></script> 
		<!-- DataTables -->
			
		<script type="text/javascript" charset="utf8" src="./media/js/jquery.dataTables.min.js"></script>

		<script type="text/javascript">
				$(document).ready(function() {
				oTable = $('#table1').dataTable({
					"bJQueryUI": true,
					"iDisplayLength": 50,
					'sPaginationType': 'full_numbers',
				});
			} );


		jQuery(document).ready(function($) {
	   		$('.popup').click(function() {
			   	  var NWin = window.open($(this).prop('href'), '', 'height=800,width=600');
			     if (window.focus)
			     {
			       NWin.focus();
			     }
			     return false;
			    });
		});
		</script>

	<script>
		function statuschange(obj)
		{
			var link = obj.getAttribute("href");
			
			$.ajax({
				url: link
			});
			$("#table1").load("manageuser.php #table1");
			return false;

		}
	</script>

</head>


<body>
	<div id="nav-bar">
		<ul>
			<li><a href="./">Home</a></li>
			<li><a href="start_node.php">View Nodes</a></li>
			<li><a href="managenode.php">Node Manager</a></li>
			<li><a href="manageuser.php">User Manager</a></li>
			<li><a href="logout.php">Logout</a></li>
	</div>
	<h1 style="color:#77903D;padding-left:20px">User Manager</h1>

		<table id="table1">
			<thead>
				<tr>
					<th width="15%"></th>
					<th width="15%">Username</th>
					<th width="25%">Email</th>
					<th width="10%">Privilege</th>
					<th width="10%">Status</th>
					<th width="10%"></th>
					<th width="15%"></th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($arr as $a)
					{
						echo '<tr>';
						echo '<td></td>';
						echo '<td>' . $a .'</td>';
						echo '<td>' . get_email_from_username($db,$a) .'</td>';
						echo '<td>' . get_user_privilege_name($db,get_privilege_from_username($db,$a)) . '</td>';
						echo '<td>' . get_status($db,$a);
						echo '<span style="float:right"><a href="./changestatus.php?name=' . $a . '" onclick=" return statuschange(this)">(switch)</a></span>';
						echo '</td>';


						$link = "./manageuserprocess.php?user=" . $a;
						echo '<td><span style="float:right">' . '<a href="' . htmlspecialchars($link) . '" class="popup">Modify</a>' . '</span></td>';
						echo "<td></td>";
						echo '</tr>';
					}


				?> 
			</tbody>
		</table>

</body>




</html>