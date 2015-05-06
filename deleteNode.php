<?php
session_start();
require 'config.php';
	//connect to thonk db
	$db = connect_thonkdb();

		if(isset($_GET['id']))
			$id = $_GET['id'];
		else header('Location: index.php');

?>

<!DOCTYPE html>
<html>
<head>
	<title>Delete Node</title>
	<meta charset="UTF-8">
	 <script src="http://d3js.org/d3.v3.min.js"></script>
  <script src="myscript.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<link href="./media/css/thonk.css" rel="stylesheet" type="text/css">
<div id="dom-target" style="display: none;">
    <?php 
    session_start();

        $output = $_SESSION['parent_id']; //Again, do some operation, get the output.
        echo ($output); /* You have to escape because the result
                                           will not be valid HTML otherwise. */
    ?>
</div>
	<script type="text/javascript">
		<!--
		function win(){
		//window.opener.location.href="start_node.php";
		/*var id = document.getElementById("dom-target").textContent;
		window.opener.make_json(id);*/
		self.close();
		//-->
		}
	</script>

</head>
<body>
	<?php
		if (get_child_node_from_id($db,$id) == null)
		{
			remove_node($db,$id);			
			echo '<br><span style="color:#77903D;font-size:2em;">Node deleted!</span>';
		}
		else echo '<br><span style="color:red">*You need to delete its child node first before deleting this node</span>';
			echo '<br><input type=button onClick="win();" value="Close">';

	?>
</body>
</html>