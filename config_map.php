<?php

			error_reporting(E_ERROR | E_PARSE);

	if(function_exists($_GET['f'])) { // get function name and parameter  
		$_GET['f']($_GET["p"]);

	} else { 
	//echo 'Method Not Exist'; 
	} 
	



	function make_json($id)
	{
		include 'config.php';
		error_reporting(E_ERROR | E_PARSE);
		$ret = "";
		
		$db = connect_thonkdb();
		$child_arr = get_child_node_from_id($db,$id);
		//print_r($child_arr);
		$jsonfile = fopen($id.".json","w");

		$content = "{\n\"name\":\"" . get_title_from_id($db,$id) . "\",
		\"children\": 
		[
	  	{
	        \"name\": \"map\",
	        \"children\": [             
	 	";
	 	



		fwrite($jsonfile, $content);
		$ret .= $content;
		
		/*$result = 0;
			//echo $value . "<br>";
			$thumb_count = thumbup_count($db,$id)+thumbdown_count($db,$id);
			if($thumb_count == 0)
			{
				$result = 90;
			}
			else{
				$temp = (thumbup_count($db,$id)/$thumb_count)*120;

				if ($temp > 120) {
					$result = 120;
				}
				elseif ($temp < 60) {
					$result = 60;
				}
				else {
					$result = $temp;
				}
			}
*/
			$parent = get_parent_from_id($db,$id);
			if ($parent == "") {
				$parent = "0";
			}
		if (count_child($db,$id) > 0) {//\"synopsis\":\"".get_synopsis_from_id($db,$id)."\",

			$content= "{\"name\":\"".get_title_from_id($db,$id)."\", \"size\":170,\"id\":\"". $id."\",\"src_url\":\"".get_sourceurl_from_id($db,$id)."\",\"img_url\":\"".get_imageurl_from_id($db,$id)."\",\"vid_url\":\"".get_videourl_from_id($db,$id)."\",\"parent_id\":\"". $parent ."\",\"up\":\"".thumbup_count($db,$id)."\",\"down\":\"".thumbdown_count($db,$id)."\",\"category\":".get_category_from_id($db,$id).", \"root\":1},\n\t"; 
			$ret .= $content;
			fwrite($jsonfile, $content);
				
		}
		elseif (count_child($db,$id) == 0) {
		$content= "{\"name\":\"".get_title_from_id($db,$id)."\", \"size\":170,\"id\":\"". $id."\",\"src_url\":\"".get_sourceurl_from_id($db,$id)."\",\"img_url\":\"".get_imageurl_from_id($db,$id)."\",\"vid_url\":\"".get_videourl_from_id($db,$id)."\",\"parent_id\":\"". $parent ."\",\"up\":\"".thumbup_count($db,$id)."\",\"down\":\"".thumbdown_count($db,$id)."\",\"category\":".get_category_from_id($db,$id).", \"root\":1}\n\t"; 
			$ret .= $content;
			fwrite($jsonfile, $content);
		}
		 

		foreach($child_arr as $value)
		{
			$result = 0;
			//echo $value . "<br>";
			$thumb_count = thumbup_count($db,$value)+thumbdown_count($db,$value);
			if($thumb_count == 0)
			{
				$result = 90;
			}
			else{
				$temp = (thumbup_count($db,$value)/$thumb_count)*120;

				if ($temp > 120) {
					$result = 120;
				}
				elseif ($temp < 60) {
					$result = 60;
				}
				else {
					$result = $temp;
				}
			}
			//$result = $result *2;




			if(end($child_arr) != $value)
			{
				$content = "{\"name\":\"".get_title_from_id($db,$value)."\", \"size\":".$result.",\"id\":\"". $value."\",\"src_url\":\"".get_sourceurl_from_id($db,$value)."\",\"img_url\":\"".get_imageurl_from_id($db,$value)."\",\"vid_url\":\"".get_videourl_from_id($db,$value)."\",\"parent_id\":\"".get_parent_from_id($db,$value)."\",\"up\":\"".thumbup_count($db,$value)."\",\"down\":\"".thumbdown_count($db,$value)."\",\"category\":".get_category_from_id($db,$value).", \"root\":0},\n\t"; 
				$ret .= $content;
				fwrite($jsonfile, $content);
			}
			else
			{
				$content = "{\"name\":\"".get_title_from_id($db,$value)."\", \"size\":".$result.",\"id\":\"". $value."\",\"src_url\":\"".get_sourceurl_from_id($db,$value)."\",\"img_url\":\"".get_imageurl_from_id($db,$value)."\",\"vid_url\":\"".get_videourl_from_id($db,$value)."\",\"parent_id\":\"".get_parent_from_id($db,$value)."\",\"up\":\"".thumbup_count($db,$value)."\",\"down\":\"".thumbdown_count($db,$value)."\",\"category\":".get_category_from_id($db,$value).", \"root\":0}\n\t"; 
				$ret .= $content;
				fwrite($jsonfile, $content);
			}

		}

		fwrite($jsonfile,"]\n\t}\n]\n}");
		$ret .= "]\n\t}\n]\n}";
		echo $ret ;

		return $ret;
	}



	function get_parent($id)
	{
		include 'config.php';
		error_reporting(E_ERROR | E_PARSE);
		$ret = "";
		
		$db = connect_thonkdb();

		$result = $db->node->findOne(array('_id' => new MongoID($id)));

		$ret = (string) $result["node_parent_id"];
		if ($result != null) {
			echo $ret;			
		}
		else
			echo "0";

	}

	function delete_json()
	{
		foreach (glob("*.json") as $filename) {
	   echo "$filename size " . filesize($filename) . "\n";
	   unlink($filename);
		}
	}


	function thumb_up($node_id)
	{
		include 'config.php';
		session_start();
		$username=  $_SESSION['username'];
		$db = connect_thonkdb();
		$user_id = get_id_from_username($db,$username);
	
		if(check_thumbdown($db,$user_id,$node_id) == true)
			remove_thumbdown($db,$user_id,$node_id);
		if (check_thumbup($db,$user_id,$node_id) == true)
			{
				//remove_thumbup($db,$user_id,$node_id);
				return 0;
			}
		try
		{
			$db->node->update(array("_id" => new MongoID($node_id)),array('$push' => array('node_thumb_up_id' => $user_id)));
			return 0;
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}


	function thumb_down($node_id)
	{
		include 'config.php';
		session_start();
		$username=  $_SESSION['username'];
		$db = connect_thonkdb();
		$user_id = get_id_from_username($db,$username);
		if(check_thumbup($db,$user_id,$node_id) == true)
			remove_thumbup($db,$user_id,$node_id);
		if (check_thumbdown($db,$user_id,$node_id) == true)
			{
				//remove_thumbdown($db,$user_id,$node_id);
				return 0;
			}
		try
		{
			$db->node->update(array("_id" => new MongoID($node_id)),array('$push' => array('node_thumb_down_id' => $user_id)));
			return 0;
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function get_parent_id_from_session()
	{
		session_start();
		echo $_SESSION['parent_id'];
	}


	//_________________________________________________________________________________________________

?>