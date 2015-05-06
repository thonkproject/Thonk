<?php
	session_start();
	include 'config.php';
	$db = connect_thonkdb();

//get the username
$username = htmlspecialchars($_POST['username']);

if(find_username($db,$username) === true)
	{echo 0;}
else
	{echo 1;}


?>