
<?php
session_start();
require 'config.php';
	$db = connect_thonkdb();

	if (!isset($_SESSION['username']))
		header('Location: login.php');
	else if(get_privilege_from_username($db,$_SESSION['username']) == 0)
	{header('Location: ./');}


	$list = $db->node->find();
	$arr = array();
	foreach ($list as $a)
		$arr[] = (string) $a['_id'];


?>

<!DOCTYPE html>
<html>
<head>
	<title>Node Manager</title>
	<meta charset="UTF-8">
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
					"iDisplayLength": 25,
					'sPaginationType': 'full_numbers',
				});
			} );


		jQuery(document).ready(function($) {
	   		$('.popup').click(function() {
			   	  var NWin = window.open($(this).prop('href'), '', 'scrollbars=1,height=800,width=600');
			     if (window.focus)
			     {
			       NWin.focus();
			     }
			     return false;
			    });
		});


		</script>
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
	<h1 style="color:#77903D;padding-left:20px">Node Manager</h1>
	<table id="table1">
		<thead>
			<tr>
				<th>Title</th>
				<th>Category</th>
				<th>Insert Node After</th>
				<th>Edit Node</th>
				<th>Delete Node</th>
			</tr>
		</thead>
		<tbody>
				<?php
				foreach ($arr as $a)
				{
					echo '<tr>';
					//echo '<td>' . $a .'</td>'; //node Id
					echo '<td>' . get_title_from_id($db,$a) .'</td>';
					echo '<td>' . get_category_name($db,get_category_from_id($db,$a)) .'</td>';
					$link = "./addNode.php?id=" . $a;
					echo '<td>' . '<a href="' . htmlspecialchars($link) . '" class="popup">Insert Node</a>'  . '</td>';


					$link3 = "./editNode.php?id=" . $a;
					if (get_category_from_id($db,$a) != 0)
						echo '<td>' . '<a href="' . htmlspecialchars($link3) . '" class="popup">Edit Node</a>' . '</td>';
					else echo '<td></td>';
					$link4 = "./deleteNode.php?id=" . $a;
					echo '<td><a onclick="return confirm("Are you sure?")" href="' . htmlspecialchars($link4) . '" class="popup">Delete Node</a></td>';

					echo '</tr>';
				}


			?>
		</tbody>
	</table>
</body>




</html>