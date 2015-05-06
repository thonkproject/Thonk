<?php
session_start();
require 'config.php';
		//connect to thonk db
	$db = connect_thonkdb();

	if (!isset($_SESSION['username']) || !($_GET["name"]))
		header('Location: login.php');
	else if(get_privilege_from_username($db,$_SESSION['username']) == 0)
	{header('Location: index.php');}

	$username = $_GET["name"];

		if (check_status($db,$username) == true)
			deactivate_user($db,$username);
		else activate_user($db,$username);

?>
