<?php
require_once("../configs/config.php");
require_once("../include/linkmonger.lib.php");

// we need functions for:
// adding comment (from form submit)
// editing comment (from form submit)
// deleting comment (from form submit)
	

if (isset($_POST['action'])) {

	switch ($_POST['action']) {

		case "add":
			$sql_add  = "INSERT into comments ";
			$sql_add .= "(link_id, comment, commenter, time)";
			$sql_add .= " VALUES ";
			$sql_add .= "(".$_POST['link_id'].", '".$db->escape($_POST['commentText'])."', '".$db->escape($_POST['commenterText'])."', now())";

			$sql_update  = "UPDATE links ";
			$sql_update .= "SET comment_count = (select count(*) from comments where link_id = ".$_POST['link_id'].") ";
			$sql_update .= "WHERE id = ".$_POST['link_id'];
	
			$db->query($sql_add);
			$db->query($sql_update);
			break;
			
		case "edit":
			// nothing ever calls this... yet
			$sql_edit  = "UPDATE comments ";
			$sql_edit .= "";
			$sql_edit .= "";
						
			//$db->query($sql_edit);
			break;
			
		case "delete":
			if ($_POST['commenter'] == $_COOKIE['who']) {
				$sql_delete  = "DELETE FROM comments ";
				$sql_delete .= "WHERE id = ".$_POST['id'];
	
				$sql_update  = "UPDATE links ";
				$sql_update .= "SET comment_count = (select count(*) from comments where link_id = ".$_POST['link_id'].") ";
				$sql_update .= "WHERE id = ".$_POST['link_id'];
	
				$db->query($sql_delete);
				$db->query($sql_update);
			}
			break;
			
		default:
			echo "you submitted data (post) but i didn't know what to do with it.";
	}

	header("Location: ".$_POST['returnURL']);

}

?>