<?php
	date_default_timezone_set('America/Chicago');
	//connect to thonk db -localserver , no credential
	// function connect_thonkdb()
	// {
	// 	//connect, new session
	// 	$connection = new MongoClient();

	// 	//select database
 // 		return $connection->thonkdb;
	// }
	function connect_thonkdb()
	{
		try
		{
		    //$connection = new Mongo('mongodb://thonk:thonkdb1@ds061518.mongolab.com:61518/thonkdb');
		    $connection = new Mongo('mongodb://thonkdb:Th0nkpassword@dbh46.mongolab.com:27467/thonkdb');
		    $database   = $connection->selectDB('thonkdb');
		    return $database;
		}
		catch(MongoConnectionException $e)
		{
		    die("Failed to connect to database ".$e->getMessage());
		}
	}


	// //GLOBAL $db 
	// $db = connect_thonkdb();
	//_________________________________________________________________________________________________
	//COLLECTION: USER
	function insert_user($db,$username,$password,$email)
	{
		$user = $db->user;
		$password = md5($password);
		$user->ensureIndex(array('user_name' => 1), array("unique" => true));
		$user->ensureIndex(array('user_password' => 1), array("unique" => false));
		$user->ensureIndex(array('user_email' => 1), array("unique" => true));
		$user->ensureIndex(array('user_privilege_id' => 1), array("unique" => false));
		$user->ensureIndex(array('user_active' => 1), array("unique" => false));

		$ins_array = array('user_name' => $username,
					'user_password' => $password,
					'user_email' => $email,
					'user_privilege_id' => 0,
					'user_active' => "inactive"
			);

		try
		{
			$user->insert($ins_array,array("w" => 1));
		}
		catch (MongoDuplicateKeyException $e)
		{
			echo $e->getMessage() . '<br>';
			echo $e->getCode() . '<br>';
		}
		
	}	

	function remove_user($db,$username)
	{
		try{
			$db->user->remove(array('user_name' => $username));
		}
		catch (MongoCursorException $e){
			echo $e->getMessage() . "<br>";
		}
	}



	function update_privilege($db,$username,$priv)
	{
		$priv = (int) $priv;
		try
		{
			$db->user->update(array('user_name' => $username),array('$set' => array('user_privilege_id' => $priv)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function update_status($db,$username,$status)
	{
		try
		{
			$db->user->update(array('user_name' => $username),array('$set' => array('user_active' => $status)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function update_password($db,$username,$newpassword)
	{
		$newpassword = md5($newpassword);
		try
		{
			$db->user->update(array('user_name' => $username),array('$set' => array('user_password' => $newpassword)));
			return 0;
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . " - Cannot update password<br>";
			return -1;
		}

	}

	function update_email($db,$username,$newemail)
	{
		try
		{
			$db->user->update(array('user_name' => $username),array('$set' => array('user_email' => $newemail)));
			return 0;
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . " - Cannot update email<br>";
			return -1;
		}

	}

	function activate_user($db,$username)
	{
		try
		{
			$db->user->update(array('user_name' => $username),array('$set' => array('user_active' => "active")));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function deactivate_user($db,$username)
	{
		try
		{
			$db->user->update(array('user_name' => $username),array('$set' => array('user_active' => "inactive")));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}


	//user_active == true 
	function login($db,$username,$password)
	{
		$col = $db->user;
		$user = htmlspecialchars($username);
		$passw = md5(htmlspecialchars($password));
		try{
			$result = $col->findOne(array('user_name' => $user,'user_password' => $passw),array('user_active' => true));
		}
		catch(MongoConnectionException $e)
		{
			echo $e->getMessage() . "<br>";
		}
		if ($result['_id'] != null && $result['_id'] != '')
			return true;
		else return false;
	}

	//check status: active/inactive
	function check_status($db,$username)
	{
		$result = $db->user->findOne(array('user_name' => $username));
		if ($result['user_active'] == "active")
			return true;
		return false;
	}

	function get_status($db,$username)
	{
		$result = $db->user->findOne(array('user_name' => $username));
		return $result['user_active'];
	}



	//NEED USER ACTIVE
	//return true exist
	function find_username($db,$username)
	{		
		$col = $db->user;
		$user = htmlspecialchars($username);
		try{
			$result = $col->findOne(array('user_name' => $user));
		}
		catch(MongoConnectionException $e)
		{
			echo $e->getMessage() . "<br>";
		}
		if ($result['user_name'] != null  && $result['user_name'] != '')
			return true;
		else return false;

	}

	//return true if existed, false if not existed
	function check_email($db,$email)
	{
		$result = $db->user->findOne(array('user_email' => $email));
		if ($result['user_email'] != null && $result['user_email'] != "")
			return true;
		else return false;
	}

	//
	function get_privilege_from_username($db,$username)
	{
		$result = $db->user->findOne(array('user_name' => $username));
		return $result['user_privilege_id'];
	}

	function get_email_from_username($db,$username)
	{
		$result = $db->user->findOne(array('user_name' => $username));
		return $result["user_email"];
	}


	function get_username_from_id($db,$id)
	{
		$result = $db->user->findOne(array('_id' => new MongoID($id)));
		return $result["user_name"];
	}

	function get_id_from_username($db,$username)
	{
		$result = $db->user->findOne(array('user_name' => $username));
		return (string) $result["_id"];
	}

	function get_password_from_username($db,$username)
	{
		$result = $db->user->findOne(array('user_name' => $username));
			return $result['user_password'];
	}




	//display all users information
	function display_user_table($db)
	{
		echo "<h2>Table: user</h2>";
		$cursor = $db->user->find();
		echo "<table>";
		echo "<tr><th>_id</th>";
		echo "<th>user_name</th>";
		echo "<th>user_password</th>";
		echo "<th>user_email</th>";
		echo "<th>user_privilege_id</th>";
		echo "<th>user_active</th></tr>";
		foreach ($cursor as $document){
			echo "<tr><td>";
			echo $document['_id'] . "</td><td>";
			echo $document["user_name"] . "</td><td>";
			echo $document["user_password"] . "</td><td>";
			echo $document["user_email"] . "</td><td>";
			//uncomment below to get user_privilege_name instead of id
			//echo get_user_privilege_name($db,$document["user_privilege_id"]) . "</td><td>";
			echo $document["user_privilege_id"] . "</td><td>";
			echo $document["user_active"] . "</td></tr>";
			}
		echo "</table>";
	}

	//_________________________________________________________________________________________________
	//COLLECTION: USER_privilege

	//function: INSERT NEW privilege
	//ID: auto update
	//Name: $priv_name
	function insert_privilege($db,$priv_name)
	{
		$privilege = $db->user_privilege;
		//0: User
		//1: Admin
		//2: Super Admin
		$privilege->ensureIndex(array('user_privilege_id' => 1), array("unique" => true));
		$privilege->ensureIndex(array('user_privilege_name' => 1), array("unique" => true));

		try
		{
			$privilege->insert(array(
				'user_privilege_id' => $db->user_privilege->count(),
				'user_privilege_name' => $priv_name
			));
		}
		catch (MongoDuplicateKeyException $e)
		{
			echo $e->getMessage() . '<br>';
		}
	}	

	//function: REMOVE A privilege THAT HAS privilege nam e= $priv_name
	//ID: auto update
	//Name: $priv_name
	function remove_privilege($db,$priv_name)
	{
		$privilege = $db->user_privilege;

		try{ $privilege->remove(array('user_privilege_name' => $priv_name), array("justOne" => true,));}
		catch (MongoCursorException $e)
		{
			echo $e->getMessage() . "<br>";
		}
	}

	function get_user_privilege_name($db,$priv_id)
	{
		$col = $db->user_privilege;
		try{
			$result = $col->findOne(array('user_privilege_id' => $priv_id));
			return $result["user_privilege_name"];
		}
		catch (MongoConnectionException $e)
		{echo $e->getMessage() . "<br>";}

	}

	//return an array of privilege id
	function get_privilege_id_array($db)
	{
		$arr = array();
		$cursor = $db->user_privilege->find();
		foreach ($cursor as $field)
			$arr[] = $field["user_privilege_id"];
		return $arr;

	}

	function display_user_privilege_table($db)
	{
		$cursor = $db->user_privilege->find();
		echo "<h2>Table: user_privilege</h2>";
		echo "<table>";
		echo "<tr>";
		echo "<th>user_privilege_id</th>";
		echo "<th>user_privilege_name</th></tr>";
		foreach ($cursor as $document){
			echo "<tr><td>";
			echo $document["user_privilege_id"] . "</td><td>";
			echo $document["user_privilege_name"] . "</td></tr>";
		}
		echo "</table>";
	}
		//_________________________________________________________________________________________________
	
	//COLLECTION: CATEGORY

	//function: INSERT CATEGORY
	//ID: auto update
	//Name: $priv_name
	function insert_category($db,$category_name)
	{

		//0: Parent
		//1: Research
		//2: Experiment
		$cat = $db->category;
		$cat->ensureIndex(array('category_id' => 1), array("unique" => true));
		$cat->ensureIndex(array('category_name' => 1), array("unique" => true));

		try
		{
			$cat->insert(array(
				'category_id' => $cat->count(),
				'category_name' => $category_name
			));
		}
		catch (MongoDuplicateKeyException $e)
		{echo $e->getMessage() . "<br>";}
	}	

	//function: REMOVE A Category THAT HAS category_name= $cat_name
	//ID: auto update
	//Name: $cat_name
	function remove_category($db,$cat_name)
	{
		$cat = $db->category;
		try
		{
			$privilege->remove(array('category_name' => $cat_name), array("justOne" => true,));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	//return: a category name from id (string)
	function get_category_name($db,$category_id)
	{
		$col = $db->category;
		try
		{
			$category_id = (int) $category_id;
			$result = $col->findOne(array('category_id' => $category_id));
			return $result["category_name"];
		}
		catch (MongoConnectionException $e)
		{echo $e->getMessage() . "<br>";}


	}

	//return an array of category id
	function get_category_id_array($db)
	{
		$arr = array();
		$cursor = $db->category->find();
		foreach ($cursor as $field)
			$arr[] = $field["category_id"];
		return $arr;

	}



	function display_category_table($db)
	{
		$cursor = $db->category->find();
		echo "<h2>Table: category</h2>";
		echo "<table>";
		echo "<tr>";
		echo "<th>category_id</th>";
		echo "<th>category_name</th></tr>";
		foreach ($cursor as $document){
			echo "<tr><td>";
			echo $document["category_id"] . "</td><td>";
			echo $document["category_name"] . "</td></tr>";
		}
		echo "</table>";
	}
	//________________________________________________________________________________________________
	//COLLECTION: NODE

	//insert title, id, category
	function insert_node($db,$title,$category_id,$parent_id)
	{
		$node = $db->node;
		$node->ensureIndex(array('node_title' => 1),array('unique' => true));
		$node->ensureIndex(array('node_category_id' => 1),array('unique' => false));

		try
		{
			$arr = array('node_title' => $title,
								'node_category_id' => $category_id,'node_parent_id' =>  (string) $parent_id, 'node_in_progress' => 0);
			$node->insert($arr);
			return $arr['_id'];
		}
		catch (MongoDuplicateKeyException $e){
			echo $e->getMessage() . "<br>";
		}
	}


	function remove_node($db,$id)
	{
		$node = $db->node;
		try
		{$node->remove(array('_id' => new MongoID($id)));}
		catch(MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}

	}


	//get tags/keywords string (with comma delimiter), return an array of keywords
	function get_tag_array($str)
	{
		$arr = explode(',',$str);
		return $arr;	
	}

	//update tags from an array
	function update_tags($db,$id,$arr)
	{
		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_tag' => $arr)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function update_title($db,$id,$title)
	{
		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_title' => $title)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function update_category($db,$id,$category)
	{
		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_category_id' => $category)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}


	function update_synopsis($db,$id,$str)
	{
		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_synopsis' => $str)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function update_source_url($db,$id,$url)
	{
		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_source_url' => $url)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function update_image_url($db,$id,$url)
	{
		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_image_url' => $url)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}
	function update_video_url($db,$id,$url)
	{
		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_video_url' => $url)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function update_creator_id($db,$id,$creator)
	{
		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_creator_id' => $creator)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function initialize_child_node($db,$id)
	{
		$empty_arr = [];
		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_thumb_up_id' => $empty_arr)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "- Adding node thumb_up variable<br>";}

		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_thumb_down_id' => $empty_arr)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "- Adding node thumb_down variable<br>";}


		try
		{
			$db->node->update(array('_id' => new MongoID($id)),array('$set' => array('node_comment' => $empty_arr)));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "- Adding comment variable<br>";}
	}

	function display_node_table($db)
	{
		$cursor = $db->node->find();
		echo "<h2>Table: node</h2>";
		echo '<table>';
		echo '<tr>';
		echo "<th>_id</th>";
		echo "<th>node_title</th>";
		echo "<th>node_category_id</th>";
		echo "<th>node_parent_id</th>";
		echo "<th>node_tag</th>";
		echo "<th>node_synopsis</th>";
		echo "<th>node_source_url</th>";
		echo "<th>node_image_url</th>";
		echo "<th>node_video_url</th>";
		echo "<th>node_creator_id</th>";
		echo "<th>node_in_progress</th>";
		echo "<th>node_thumb_up_id</th>";
		echo "<th>node_thumb_down_id</th>";
		echo "<th>node_last_modified</th>";
		echo "<th>node_comment</th>";

		echo '</tr>';
		foreach ($cursor as $document){
			echo "<tr><td>";
			echo $document["_id"] . "</td>";
			echo "<td>" . $document["node_title"] . "</td>";
			echo "<td>" . $document["node_category_id"] . "</td>";
			echo "<td>" . $document["node_parent_id"] . "</td>";
			echo "<td>";
			foreach ($document["node_tag"] as $tag)
				echo $tag . ", ";
			echo "</td>";

			if ($document["node_category_id"] != 0)
			{
				echo "<td>" . $document["node_synopsis"];
				echo "<td>" . $document["node_source_url"] . "</td>";
				echo "<td>" . $document["node_image_url"] . "</td>";
				echo "<td>" . $document["node_video_url"] . "</td>";
				echo "<td>" . $document["node_creator_id"] . "</td>";
				echo "<td>" . (int)$document["node_in_progress"] . "</td>";

				echo "<td>";
				foreach ($document["node_thumb_up_id"] as $id)
					echo $id . ", ";
				echo "</td>";

				echo "<td>";
				foreach ($document["node_thumb_down_id"] as $id)
					echo $id . ", ";
				echo "</td>";

				echo "<td>";
				foreach ($document["node_last_modified"] as $last_modified)
				{
					// echo $last_modified["node_last_modified_user_id"] . "; ";
					// echo $last_modified["node_last_modified_timestamp"];					
					echo $last_modified . "<br>";					
				}
				echo "</td>";
				echo "<td>";
				foreach($document["node_comment"] as $node_comment)
				{
					foreach($node_comment as $comment)
					{
						echo $comment . "<br>";
					}
				}
				echo" </td>";

			}
			else echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";

			
			echo "</tr>";
		}
		echo "</table>";
	}

	//return category id , given node id
	function get_category_from_id($db,$id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($id)));
		return $result["node_category_id"];
	}

	//given node id, return title
	function get_title_from_id($db,$id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($id)));
		return $result["node_title"];
	}

	function get_id_from_title($db,$title)
	{
		$result = $db->node->findOne(array('node_title' => $title));
		return $result['_id'];
	}

	//return an array of of child nodes' ID, given parent ID
	function get_child_node_from_id($db,$id)
	{
		$arr = array();
		$result = $db->node->find(array('node_parent_id' => $id));
		foreach ($result as $field)
			$arr[] = (string) $field['_id'];
		return $arr;
	}

	function get_parent_from_id($db,$id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($id)));
		return (string) $result["node_parent_id"];
	}

	//return ARRAY of ROOTS (BIGGEST PARENTS)
	function get_roots($db)
	{
		$arr = array();
		$result = $db->node->find(array('node_parent_id' => null));
		foreach ($result as $field)
			$arr[] = (string) $field['_id'];
		return $arr;
	}


	function thumbup($db,$user_id,$node_id)
	{
		if(check_thumbdown($db,$user_id,$node_id) == true)
			remove_thumbdown($db,$user_id,$node_id);
		if (check_thumbup($db,$user_id,$node_id) == true)
			{
				remove_thumbup($db,$user_id,$node_id);
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

	function thumbdown($db,$user_id,$node_id)
	{
		if(check_thumbup($db,$user_id,$node_id) == true)
			remove_thumbup($db,$user_id,$node_id);
		if (check_thumbdown($db,$user_id,$node_id) == true)
			{
				remove_thumbdown($db,$user_id,$node_id);
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


	function remove_thumbup($db,$user_id,$node_id)
	{
		// if (check_thumbup($db,$user_id,$node_id) == false)
		// 	{return 0;}
		
		try
		{
			$db->node->update(array("_id" => new MongoID($node_id)),array('$pull' => array('node_thumb_up_id' => $user_id)));
			return 0;
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function remove_thumbdown($db,$user_id,$node_id)
	{
		// if (check_thumbup($db,$user_id,$node_id) == false)
		// 	{return 0;}
		
		try
		{
			$db->node->update(array("_id" => new MongoID($node_id)),array('$pull' => array('node_thumb_down_id' => $user_id)));
			return 0;
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	function check_thumbup($db,$user_id,$node_id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($node_id)));
		if ($result['node_thumb_up_id'] == null || $result['node_thumb_up_id'] == '')
			return false;
		foreach ($result['node_thumb_up_id'] as $val)
		{
			if ($val == $user_id)
				return true;
		}
		return false;
	}

	function check_thumbdown($db,$user_id,$node_id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($node_id)));
		if ($result['node_thumb_down_id'] == null || $result['node_thumb_down_id'] == '')
			return false;
		foreach ($result['node_thumb_down_id'] as $val)
		{
			if ($val == $user_id)
				return true;
		}
		return false;
	}

	function thumbup_count($db,$id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($id)));
		return count($result['node_thumb_up_id']);
	}
	
	function thumbdown_count($db,$id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($id)));
		return count($result['node_thumb_down_id']);
	}


	//return array of tags/keywords from given ID
	function get_tag_from_id($db,$id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($id)));
		$arr = array();
		foreach ($result['node_tag'] as $field)
			$arr[] = $field;
		return $arr;
	}

	function get_synopsis_from_id($db,$id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($id)));
		return $result["node_synopsis"];
	}

	function get_sourceurl_from_id($db,$id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($id)));
		return $result["node_source_url"];
	}

	function get_videourl_from_id($db,$id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($id)));
		return $result["node_video_url"];
	}

	function get_imageurl_from_id($db,$id)
	{
		$result = $db->node->findOne(array('_id' => new MongoID($id)));
		return $result["node_image_url"];
	}


	function insert_comment($db,$node_id,$cmt,$user_id)
	{
		$node = $db->node;
		$node_id = new MongoID($node_id);
		$arr = array('node_comment' => array('comment_user_id' => $user_id,'comment_desc' => $cmt, 'comment_timestamp' => date(DATE_RFC2822)));
		try
		{
			$node->update(array("_id" => $node_id),array('$push' => $arr));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}
	//return array of comment objects
	function get_comment($db,$node_id)
	{
		$node = $db->node;
		$node_id = new MongoID($node_id);
		$arr = array();
		$result = $node->findOne(array('_id' => $node_id));
		foreach($result['node_comment'] as $a)
		{
			$arr[] = $a;
		}
		return $arr;
	}

	function update_last_modified($db,$node_id,$user_id)
	{
		$node = $db->node;
		$node_id = (string) $node_id;
		$node_id = new MongoID($node_id);
		$arr = array( 'node_last_modified' => array('last_modified_user_id' => $user_id, 'last_modfied_timestamp' => date(DATE_RFC2822)));
		try
		{
			$node->update(array("_id" => $node_id),array('$set' => $arr));
		}
		catch (MongoCursorException $e)
		{echo $e->getMessage() . "<br>";}
	}

	//March 30
	function count_child($db,$parent_id)
	{

		$result = $db->node->count(array('node_parent_id' => $parent_id));
		return $result;
		
	}

	//_________________________________________________________________________________________________



function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
?>