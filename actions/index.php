<?php
	session_start();
	if (isset($_GET['id']) && isset($_GET['action']))
	{
		$user = $_GET['id'];
		$do = $_GET['action'];
	}
	else
	{
		header('Location: ../');
	}

	include '../config.php';
	$db = connect_thonkdb();

	$name = get_username_from_id($db,$user);

	if (get_status($db,$name) == "inactive")
	{
		if($do == "activateuser")
		{
			activate_user($db,$name);
			header('Location: ../login.php');
		}
		else if ($do == "verifyemail")
		{
			//send email here
			$url = "../eVeryfication.php?u=" . $user;
			header('Location:' . $url);
		}
	}

?>